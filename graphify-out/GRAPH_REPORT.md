# Graph Report - Finance  (2026-10-24)

## Corpus Check
- 365 files · ~250,042 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1025 nodes · 1228 edges · 247 communities (216 shown, 31 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 39 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_AbstractCommand|AbstractCommand]]
- [[_COMMUNITY_Queueable|Queueable]]
- [[_COMMUNITY_HasFactory|HasFactory]]
- [[_COMMUNITY_PageController|PageController]]
- [[_COMMUNITY_User|User]]
- [[_COMMUNITY_composer.json|composer.json]]
- [[_COMMUNITY_dependencies|dependencies]]
- [[_COMMUNITY_TelegramBot|TelegramBot]]
- [[_COMMUNITY_app.js|app.js]]
- [[_COMMUNITY_devDependencies|devDependencies]]
- [[_COMMUNITY_Migration|Migration]]
- [[_COMMUNITY_ServiceProvider|ServiceProvider]]
- [[_COMMUNITY_Faker|Faker]]
- [[_COMMUNITY_AuthController|AuthController]]
- [[_COMMUNITY_TelegramBotContainer|TelegramBotContainer]]
- [[_COMMUNITY_README|README.md]]
- [[_COMMUNITY_Middleware|Middleware]]
- [[_COMMUNITY_.compose|.compose]]
- [[_COMMUNITY_Kernel.php|Kernel.php]]
- [[_COMMUNITY_Controller|Controller]]
- [[_COMMUNITY_TaskController|TaskController]]
- [[_COMMUNITY_TestCase|TestCase]]
- [[_COMMUNITY_SettingMasterPasswordController|SettingMasterPasswordController]]
- [[_COMMUNITY_CommandTrait.php|CommandTrait.php]]
- [[_COMMUNITY_SettingGeneralController|SettingGeneralController]]
- [[_COMMUNITY_EnsureTokenIsValid.php|EnsureTokenIsValid.php]]
- [[_COMMUNITY_webpack.mix.js|webpack.mix.js]]
- [[_COMMUNITY_Handler|Handler]]
- [[_COMMUNITY_DarkModeComposer.php|DarkModeComposer.php]]
- [[_COMMUNITY_step-three.blade.php|step-three.blade.php]]
- [[_COMMUNITY_academic.blade.php|academic.blade.php]]
- [[_COMMUNITY_const.js|const.js]]
- [[_COMMUNITY_manage-learning-formats.blade.php|manage-learning-formats.blade.php]]
- [[_COMMUNITY_manage-moodle-courses.blade.php|manage-moodle-courses.blade.php]]
- [[_COMMUNITY_manage-subjects.blade.php|manage-subjects.blade.php]]
- [[_COMMUNITY_add_children.blade.php|add_children.blade.php]]
- [[_COMMUNITY_change-goals.blade.php|change-goals.blade.php]]
- [[_COMMUNITY_change-learning-format.blade.php|change-learning-format.blade.php]]
- [[_COMMUNITY_change_mentality.blade.php|change_mentality.blade.php]]
- [[_COMMUNITY_change_skill.blade.php|change_skill.blade.php]]
- [[_COMMUNITY_change_training_format.blade.php|change_training_format.blade.php]]
- [[_COMMUNITY_stage-null.blade.php|stage-null.blade.php]]
- [[_COMMUNITY_stage-one.blade.php|stage-one.blade.php]]
- [[_COMMUNITY_stage-one-old.blade.php|stage-one-old.blade.php]]
- [[_COMMUNITY_step-five.blade.php|step-five.blade.php]]
- [[_COMMUNITY_side-menu.blade.php|side-menu.blade.php]]
- [[_COMMUNITY_simple-menu.blade.php|simple-menu.blade.php]]
- [[_COMMUNITY_Constants.php|Constants.php]]
- [[_COMMUNITY_add_children_step_3.blade.php|add_children_step_3.blade.php]]
- [[_COMMUNITY_stage-two.blade.php|stage-two.blade.php]]
- [[_COMMUNITY_step-six.blade.php|step-six.blade.php]]
- [[_COMMUNITY_login.blade.php|login.blade.php]]
- [[_COMMUNITY_main.blade.php|main.blade.php]]
- [[_COMMUNITY_top-menu.blade.php|top-menu.blade.php]]
- [[_COMMUNITY_tailwind.config.js|tailwind.config.js]]

## God Nodes (most connected - your core abstractions)
1. `PageController` - 60 edges
2. `User` - 36 edges
3. `TelegramBot` - 30 edges
4. `Controller` - 25 edges
5. `AbstractCommand` - 21 edges
6. `Faker` - 17 edges
7. `Transaction` - 14 edges
8. `ProcessNotificationParents` - 12 edges
9. `Year` - 12 edges
10. `TelegramBotContainer` - 12 edges

## Surprising Connections (you probably didn't know these)
- `SettingGeneralController` --inherits--> `Controller`  [EXTRACTED]
  Finance/app/Http/Controllers/Admin/SettingGeneralController.php → Finance/app/Http/Controllers/Controller.php
- `SettingMasterPasswordController` --inherits--> `Controller`  [EXTRACTED]
  Finance/app/Http/Controllers/Admin/SettingMasterPasswordController.php → Finance/app/Http/Controllers/Controller.php
- `UserController` --inherits--> `Controller`  [EXTRACTED]
  Finance/app/Http/Controllers/Admin/UserController.php → Finance/app/Http/Controllers/Controller.php
- `AuthController` --inherits--> `Controller`  [EXTRACTED]
  Finance/app/Http/Controllers/AuthController.php → Finance/app/Http/Controllers/Controller.php
- `CategoryController` --inherits--> `Controller`  [EXTRACTED]
  Finance/app/Http/Controllers/CategoryController.php → Finance/app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (247 total, 31 thin omitted)

### Community 0 - "AbstractCommand"
Cohesion: 0.05
Nodes (21): Constants, AbstractCommand, BalanceCommand, BalanceDepositCommand, ContactCommand, CurrencyFromCommand, CurrencyFromToCommand, DirectionInformationCommand (+13 more)

### Community 1 - "Queueable"
Cohesion: 0.09
Nodes (21): EmailHelpers, ProcessCheckLiqpayPaymentsStatus, ProcessChildNextClass, ProcessGetMoodle, ProcessNotificationResetPassword, ProcessParentBlockCabinet, ProcessSendingDataToBitrix, ProcessSendingEmail (+13 more)

### Community 2 - "HasFactory"
Cohesion: 0.08
Nodes (19): CategoryController, Request, ExchangeController, Request, HomeController, MonthController, Request, Request (+11 more)

### Community 4 - "User"
Cohesion: 0.06
Nodes (17): CreateUserCommand, Request, SmsInterface, UserController, SmsInterface, ProcessNotificationParents, User, Authenticatable (+9 more)

### Community 5 - "composer.json"
Cohesion: 0.04
Nodes (45): autoload, autoload-dev, psr-4, psr-4, config, optimize-autoloader, preferred-install, sort-packages (+37 more)

### Community 6 - "dependencies"
Cohesion: 0.05
Nodes (41): dependencies, cash-dom, chart.js, @ckeditor/ckeditor5-autoformat, @ckeditor/ckeditor5-basic-styles, @ckeditor/ckeditor5-block-quote, @ckeditor/ckeditor5-cloud-services, @ckeditor/ckeditor5-easy-image (+33 more)

### Community 7 - "TelegramBot"
Cohesion: 0.08
Nodes (9): Request, TelegramController, ContainerInterface, Processing, RedisManager, Request, TranslatorInterface, TelegramBot (+1 more)

### Community 8 - "app.js"
Cohesion: 0.06
Nodes (5): tiny-slider, token, editorConfig, simpleEditorConfig, helpers

### Community 9 - "devDependencies"
Cohesion: 0.06
Nodes (35): author, description, devDependencies, autoprefixer, axios, browser-sync, browser-sync-webpack-plugin, @ckeditor/ckeditor5-dev-utils (+27 more)

### Community 10 - "Migration"
Cohesion: 0.08
Nodes (9): CreateUsersTable, CreatePasswordResetsTable, CreateFailedJobsTable, CreateJobsTable, CreateCategoryTable, CreateYearTable, CreateMonthTable, CreateTaskTable (+1 more)

### Community 11 - "ServiceProvider"
Cohesion: 0.09
Nodes (10): Closure, Request, RedirectIfAuthenticated, AppServiceProvider, AuthServiceProvider, BroadcastServiceProvider, EventServiceProvider, RouteServiceProvider (+2 more)

### Community 12 - "Faker"
Cohesion: 0.09
Nodes (5): Faker, FakerComposer, View, LoggedInUserComposer, View

### Community 13 - "AuthController"
Cohesion: 0.11
Nodes (8): AuthController, Request, Admin, Closure, Authenticate, Closure, LoginRequest, FormRequest

### Community 14 - "TelegramBotContainer"
Cohesion: 0.16
Nodes (10): ContainerInterface, Processing, RedisManager, TranslatorInterface, TelegramBotContainer, EmailHelper, EmailSenderService, EmailsRepository (+2 more)

### Community 15 - "README.md"
Cohesion: 0.13
Nodes (14): jamm, jamm, jamm, jamm, jamm, jamm, jamm, jamm (+6 more)

### Community 16 - "Middleware"
Cohesion: 0.19
Nodes (7): EncryptCookies, PreventRequestsDuringMaintenance, TrimStrings, TrustHosts, TrustProxies, VerifyCsrfToken, Middleware

### Community 17 - ".compose"
Cohesion: 0.24
Nodes (5): MenuComposer, View, SideMenu, SimpleMenu, TopMenu

### Community 18 - "Kernel.php"
Cohesion: 0.20
Nodes (5): Kernel, Kernel, ConsoleKernel, HttpKernel, Schedule

### Community 19 - "Controller"
Cohesion: 0.27
Nodes (7): Request, SettingController, Controller, AuthorizesRequests, BaseController, DispatchesJobs, ValidatesRequests

### Community 20 - "TaskController"
Cohesion: 0.31
Nodes (3): Request, TaskController, Task

### Community 21 - "TestCase"
Cohesion: 0.24
Nodes (5): BaseTestCase, CreatesApplication, ExampleTest, TestCase, ExampleTest

### Community 25 - "EnsureTokenIsValid.php"
Cohesion: 0.60
Nodes (3): EnsureTokenIsValid, Closure, Request

### Community 26 - "webpack.mix.js"
Cohesion: 0.40
Nodes (4): CKERegex, mix, { styles }, tailwindcss

### Community 29 - "step-three.blade.php"
Cohesion: 0.50
Nodes (3): constant, js_function, progress

## Knowledge Gaps
- **173 isolated node(s):** `Constants`, `Constants`, `name`, `type`, `description` (+168 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **31 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Controller` to `HasFactory`, `PageController`, `User`, `TelegramBot`, `AuthController`, `TaskController`, `SettingMasterPasswordController`, `SettingGeneralController`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Queueable`, `HasFactory`, `TaskController`, `TelegramBot`?**
  _High betweenness centrality (0.076) - this node is a cross-community bridge._
- **Why does `TelegramBot` connect `TelegramBot` to `AbstractCommand`, `TelegramBotContainer`?**
  _High betweenness centrality (0.062) - this node is a cross-community bridge._
- **What connects `Constants`, `Constants`, `name` to the rest of the system?**
  _173 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `AbstractCommand` be split into smaller, more focused modules?**
  _Cohesion score 0.05179982440737489 - nodes in this community are weakly interconnected._
- **Should `Queueable` be split into smaller, more focused modules?**
  _Cohesion score 0.08701923076923077 - nodes in this community are weakly interconnected._
- **Should `HasFactory` be split into smaller, more focused modules?**
  _Cohesion score 0.07514124293785311 - nodes in this community are weakly interconnected._