<?php
/*
*/
namespace App\Jobs;

use App\Models\Children;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;
use App\Helpers\LiqPay;
use Illuminate\Support\Facades\Log;

class ProcessCheckLiqpayPaymentsStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $bx24;
    private $liqpay;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->bx24 = new Bitrix24API('https://jamm.bitrix24.eu/rest/9810/yf2nyp6n0j1k65hc/');
        $this->liqpay = new LiqPay(config('liqpay.public'), config('liqpay.private'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::info('Start ProcessCheckLiqpayPaymentsStatus ...');
        $generator = $this->bx24->fetchDealList([
            'STAGE_ID' => 'C16:PREPAYMENT_INVOIC',
            'UF_COMPANY_ID' => 9070,
            'CONTACT_ID' => 17650]
        );

        foreach ($generator as $deals) {
            foreach($deals as $deal) {
                Log::info('Checking deal '.$deal['ID'].', current status: '.$deal['STAGE_ID']);
                $invoices = $this->bx24->request('crm.invoice.list', [
                    'filter' => [
                        'UF_DEAL_ID' => $deal['ID'],
                        'PAY_SYSTEM_ID' => 8
                    ]
                ]);
                if(!empty($invoices)){
                    if(count($invoices) > 1){
                        Log::warning('There are more than one invoice for the deal '.$deal['ID']);
                        continue;
                    }

                    Log::info('Processing invoice '.$invoices[0]['ID'].' with liqpay payment id '.$invoices[0]['PAY_VOUCHER_NUM']);
                    if(empty($invoices[0]['PAY_VOUCHER_NUM'])){
                        Log::warning('Empty PAY_VOUCHER_NUM');
                        continue;
                    }

                    $response = $this->liqpay->api("request", array(
                        'action'        => 'status',
                        'version'       => '3',
                        'payment_id'      => $invoices[0]['PAY_VOUCHER_NUM']
                    ));
                    Log::info('Response from LiqPay '.print_r($response, 1));

                    if(!empty($response)){
                        switch ($response->status) {
                            case 'success':
                                Log::info('Change STAGE_ID '.$deal['STAGE_ID'].' -> C16:WON');
                                $this->bx24->updateDeal($deal['ID'], [
                                    'STAGE_ID' => "C16:WON",
                                    // 'CATEGORY_ID' => 16
                                ]);
                                break;
                            case 'error':
                            case 'failure':
                            case 'reversed':
                                // code...
                                break;
                            default:
                                Log::info('Change STAGE_ID '.$deal['STAGE_ID'].' -> C16:PREPAYMENT_INVOIC');
                                $this->bx24->updateDeal($deal['ID'], [
                                    'STAGE_ID' => "C16:PREPAYMENT_INVOIC",
                                    // 'CATEGORY_ID' => 16
                                ]);
                                break;
                        }
                    }else{
                        Log::warning('Empty response from LiqPay');
                    }
                    sleep(2);
                }else{
                    Log::info('No invoice has been issued for the deal');
                }
            }
        }
        Log::info('End ProcessCheckLiqpayPaymentsStatus');
    }
}
