<?php

namespace App\Jobs;

use App\Helpers\EmailHelpers;
use App\Helpers\FunctionHelpers;
use App\Helpers\CronHelpers;
use App\Models\Children;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;
use Illuminate\Support\Facades\Log;  // SBIT AO

class ProcessSendingDataToBitrix implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $method;
    private $id;
    private $bx24;
    private $fields;
    private $value;
    private $liqpay_payment_id; // SBIT AO
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($method, $id, $fields, $value = null, $liqpay_payment_id = null) // SBIT AO: added param $this->liqpay_payment_id = null
    {
        $this->method   = $method;
        $this->fields   = $fields;
        $this->value    = $value;
        $this->id       = $id;
        $this->liqpay_payment_id = $liqpay_payment_id; // SBIT AO
        // $this->bx24     = new Bitrix24API('https://jamm.bitrix24.eu/rest/8503/kyvpl3yufv14r1q2/');
        $this->bx24     = new Bitrix24API(env('BITRIX24WEBHOOK')); //SBIT WF
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $month = [
            "07" => '5126',
            "08" => '5126',
            "09" => '5126',
            "10" => '5128',
            "11" => '5130',
            "12" => '5132',
            "01" => '5134',
            "02" => '5136',
            "03" => '5138',
            "04" => '5140',
            "05" => '5142',
            "06" => '5144',
        ];
        FunctionHelpers::toTelegram(2);
        switch ($this->method) {
            case 'updateContact':
                $this->bx24->updateContact($this->id, $this->fields);
                break;
            case 'addDeal':
                $children = Children::where('id', $this->id)->first();
                if ($children->deal_id === null) {
                    $dealArray = [
                        'TITLE'                 => 'Угода ' . $children->name . ' - ' . $children->bx_id,
                        'COMPANY_ID'            => $children->user->bx_id,
                        'CONTACT_ID'            => $children->bx_id,

                        'STAGE_ID'              => $children->user->status === null ? 'C13:NEW' : "C16:NEW",
                        // 'CATEGORY_ID'           => $children->user->status===null?'13':"9", // SBIT AO
                        'CATEGORY_ID'           => "16", // SBIT AO
                        'UF_CRM_1623755208971'  => $this->value,
                        'UF_CRM_1625650483811'  => $month[date('m')],
                    ];
                    FunctionHelpers::toTelegram(json_encode($dealArray));
                    $deal = $this->bx24->addDeal($dealArray);
                    FunctionHelpers::toTelegram(json_encode($dealArray));
                    $children->update(['deal_id' => $deal]);
                    $this->bx24->setDealProductRows($deal, $this->fields);
                }
                break;
            case 'updateDeal':
                $this->bx24->updateDeal($this->id, $this->fields);
                break;
                // SBIT AO (
            case 'setProductRow':
                $cron   = new CronHelpers();
                $product = $cron->product($this->fields);
                // Log::info('product: '.print_r($product, 1));
                $this->bx24->setDealProductRows($deal, $product);
                break;
                // ) SBIT
            case 'updateCompany':
                $this->bx24->updateCompany($this->id, $this->fields);
                break;
            case 'invoiceDeal':
                $products = $this->bx24->getDealProductRows($this->id);

                $invoice_id = $this->bx24->request('crm.invoice.add', [
                    'fields' => [
                        'ORDER_TOPIC'           => 'Рахунок по угоді ' . $this->id . ' ' . date('Y.m.d'),
                        'ACCOUNT_NUMBER'        => uniqid(),
                        'PAY_VOUCHER_NUM'       => $this->liqpay_payment_id, // SBIT AO: changed uniqid() -> $this->liqpay_payment_id
                        'RESPONSIBLE_ID'        => 8503,
                        'PERSON_TYPE_ID'        => 1,
                        'UF_DEAL_ID'            => $this->id,
                        "STATUS_ID"             => "P",
                        "PAY_SYSTEM_ID"         => "8",
                        'PRODUCT_ROWS'          => $products,
                        'UF_COMPANY_ID'         => $this->fields->user->bx_id,
                        'UF_CONTACT_ID'         => $this->fields->bx_id,
                    ]
                ]);
                // SBIT AO (
                $this->bx24->updateDeal($this->id, [
                    'UF_CRM_1635233516' => $invoice_id
                ]);
                // ) SBIT AO
                break;
        }
    }
}
