<?php

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'Modules\Collection\Http\Controllers'], function()
{
      /*datatables url POST method*/
    Route::post('/set_datatables', 'DataTableController@set_datatables')->name('collection.set_datatables');


    Route::resource('serial', 'SerialController',
        ['only' => ['store', 'index', 'show', 'destroy', 'update', 'edit']]
    );

    Route::resource('serialsg', 'SerialControllerSg',
        ['only' => ['store', 'index', 'show', 'destroy', 'update', 'edit']]
    );

    Route::resource('receipt', 'TransactionController', ['except' => ['create']]);
    Route::resource('cash_division', 'CashDivisionController');
    Route::resource('field_land_tax', 'FieldLandTaxController', ['except' => ['create']]);
    Route::resource('bac', 'BacController');
    Route::post('receipt/cancel/{id}', 'TransactionController@cancel')->name('receipt.cancel');
    Route::post('field_land_tax/cancel/{id}', 'FieldLandTaxController@cancel')->name('field_land_tax.cancel');
    Route::resource('customer', 'CustomerController');
    Route::resource('account_category', 'AccountController');
    Route::resource('account_group', 'AccountGroupController');
    Route::resource('account_title', 'AccountTitleController');

    Route::get('account_title/{id}/destroy', 'AccountTitleController@destroy')->name('account_title.destroy');

     Route::get('receipt/{id}/another', 'TransactionController@another')->name('receipt.another');
     Route::post('receipt/{id}/another', 'TransactionController@another_save')->name('receipt.another_save');

     // Route::get('receipt/{id}/restore', 'TransactionController@restore')->name('receipt.restore');
     Route::post('receipt_restore', 'TransactionController@restore')->name('receipt.restore');

    Route::resource('account_subtitle', 'AccntSubController');
    Route::get('account_subtitle/{id}/destroy', 'AccntSubController@destroy')->name('account_subtitle.destroy');

     Route::resource('account_subtitle_items', 'AccntSubItemsController');
     Route::get('account_subtitle_items/{id}/destroy', 'AccntSubItemsController@destroy')->name('account_subtitle_items.destroy');
    Route::resource('budget_estimate', 'BudgetEstimateController');
    Route::resource('monthly_provincial_income', 'MonthlyProvincialIncomeController',
        ['only' => ['store', 'index', 'create']]
    );
    Route::get('monthly_provincial_income/{year}/{month}', 'MonthlyProvincialIncomeController@show')->name('monthly_provincial_income.show');
    Route::get('monthly_provincial_income/{year}/{month}/edit', 'MonthlyProvincialIncomeController@edit')->name('monthly_provincial_income.edit');
    Route::post('monthly_provincial_income/{year}/{month}/edit', 'MonthlyProvincialIncomeController@update')->name('monthly_provincial_income.update');

    Route::post('monthly_provincial_income_agenerate/', 'MonthlyProvincialIncomeController@aut_gen')->name('monthly_provincial_income.auto_gen');
    Route::post('monthly_provincial_income_regenerate/', 'MonthlyProvincialIncomeController@aut_regen')->name('monthly_provincial_income.auto_regen');
    Route::post('monthly_provincial_income_delete/', 'MonthlyProvincialIncomeController@auto_regen_delete')->name('monthly_provincial_income.auto_regen_delete');

    Route::resource('holiday_settings', 'WeekdayHolidayController',
        ['only' => ['store', 'index', 'create']]
    );
    Route::get('holiday_settings/{year}/{month}/edit', 'WeekdayHolidayController@edit')->name('holiday_settings.edit');
    Route::post('holiday_settings/{year}/{month}/edit', 'WeekdayHolidayController@update')->name('holiday_settings.update');

  Route::get('rates', 'CollectionRateController@index')->name('rates.index');
  Route::get('rates/{type}/{id}', 'CollectionRateController@edit')->name('rates.edit');
  Route::post('rates/update', 'CollectionRateController@update')->name('rates.update');

    Route::resource('receipt.certificate', 'CertificateController',
        ['only' => ['index', 'store']]
    );

    /**
     * Ammusement Reports
     */
    Route::get('/amusements_report', 'AmusementReportController@index')->name('report.amusements_report');
    Route::post('/amusements_report', 'AmusementReportController@gen_report')->name('report.amusements_genreport');

    // Route::get('/report_officers', 'ReportOfficersController@index')->name('settings_report_officers.index');
    // Route::post('/report_officers', 'ReportOfficersController@update')->name('settings_report_officers.update');
    Route::get('/report_officers', 'ReportOfficersNewController@index')->name('settings_report_officers.index');
    Route::post('/report_officers/update', 'ReportOfficersNewController@update')->name('settings_report_officers_new.update');
    Route::post('/report_officers/delete', 'ReportOfficersNewController@new_destroy')->name('settings_report_officers_new.delete_new'); 
    Route::post('/report_officers/restore', 'ReportOfficersNewController@new_restore')->name('settings_report_officers_new.restore_new');
    Route::get('/report_officers/create', 'ReportOfficersNewController@create')->name('settings_report_officers_new.create');
    Route::get('/report_officers/edit/{id}', 'ReportOfficersNewController@edit')->name('settings_report_officers_new.edit');
    Route::post('/report_officers/store', 'ReportOfficersNewController@store')->name('settings_report_officers_new.store');

    Route::get('/ada_settings', 'AdaSettingsController@index')->name('settings_ada.index');
    Route::post('/ada_settings', 'AdaSettingsController@update')->name('settings_ada.update');

    Route::get('/receipt/{id}/form_56_detail', 'TransactionController@f56_detail_form')->name('receipt.f56_detail_form');
    Route::post('/receipt/{id}/form_56_detail', 'TransactionController@f56_Detail_add')->name('receipt.f56_detail_submit');
    Route::get('/field_land_tax/{id}/form_56_detail', 'FieldLandTaxController@f56_detail_form')->name('field_land_tax.f56_detail_form');
    Route::post('/field_land_tax/{id}/form_56_detail', 'FieldLandTaxController@f56_Detail_add')->name('field_land_tax.f56_detail_submit');

  Route::get('/report/provincial_income', 'ReportController@provincial_income')->name('report.provincial_income');
    Route::get('/report/collections_deposits', 'ReportController@collections_deposits')->name('report.collections_deposits');
    Route::get('/report/real_property', 'ReportController@real_property')->name('report.real_property');
    Route::get('/report/shared', 'ReportController@shared')->name('report.shared');

    Route::get('/receipt/{id}/certificate/pdf/{gov}/{ppr_size}/{height?}/{width?}', 'PdfController@certificate')->name('pdf.cert');
    Route::get('/pdf/receipt/{id}', 'PdfController@receipt')->name('pdf.receipt');
    // Route::get('/pdf/provincial_income/', 'PdfController@provincial_income')->name('pdf.provincial_income');
    Route::get('/pdf/provincial_income/', 'PdfController@provincial_income_new2')->name('pdf.provincial_income'); // Sept 2020 revision
    Route::get('/pdf/collections_deposits/', 'PdfController@collections_deposits')->name('pdf.collections_deposits');
    Route::get('/pdf/real_property/', 'PdfController@real_property')->name('pdf.real_property');
    Route::get('/pdf/real_property/search/{report_num}/{municipality}', 'PdfController@rpt_report_search')->name('pdf.real_property_search');
    Route::get('/pdf/real_property_consolidated/', 'PdfController@real_property_consolidated')->name('pdf.real_property_consolidated');
    Route::get('/pdf/real_property_p2/', 'PdfController@real_property_p2')->name('pdf.real_property_p2');
    Route::get('/pdf/land_tax_collection/{nsign}/{id}', 'Form56Controller@print_receipt')->name('pdf.land_tax_collection');
    Route::get('/pdf/land_tax_collection/{id}', 'Form56Controller@print_receipt3')->name('pdf.land_tax_collection2');
    Route::get('/pdf/land_tax_collection2/{id}', 'Form56Controller@print_receipt2')->name('pdf.land_tax_collection3');
    Route::get('/pdf/form56_certificate/{id}', 'Form56Controller@print_certificate')->name('pdf.form56_certificate');
    Route::post('collection/ajax','CollectionAjaxController@index')->name('collection.ajax');

    Route::get('collection/datatables/{category}', 'CollectionDatatablesController@getdata')->name('collection.datatables');



    /**
     * Access PC mac address
     */

    Route::get('get_pc_mac_addressess', 'PCMacAddressController@get_pc_mac')->name('settings_access.get_pc');
    Route::post('get_pc_mac_addressess', 'PCMacAddressController@set_pc_mac')->name('settings_access.set_pc');
    Route::post('get_pc_mac_addressessf56', 'PCMacAddressController@set_pc_macf56')->name('settings_access.set_pcf56');
    Route::post('get_pc_edit', 'PCMacAddressController@get_pc_edit')->name('settings_access.get_pc_edit');
    Route::post('get_pc_edit56', 'PCMacAddressController@get_pc_edit56')->name('settings_access.get_pc_edit56');
    Route::post('update_pcf56', 'PCMacAddressController@update_pcf56')->name('settings_access.update_pcf56');
     Route::post('delete_pc_id', 'PCMacAddressController@delete_pc_id')->name('settings_access.delete_pc_id');
    Route::post('get_pc_serials', 'PCMacAddressController@get_pc_serials')->name('settings_access.get_pc_serials');
    Route::post('get_pc_serialsf56', 'PCMacAddressController@get_pc_serialsf56')->name('settings_access.get_pc_serialsf56');

    /**
     * Accounts Access
     */
    Route::get('account_access', 'AccountsAccessController@index')->name('account_access.index');
    Route::post('account_access', 'AccountsAccessController@set_account')->name('account_access.set_account');


    /**
     * Accounts Reports
     */
    Route::get('/report/AccountsReportDailyShare', 'ReportController@shared')->name('report.shared');
    Route::get('/report/AccountsReport','AccountsReportController@index')->name('report.accounts_report');
    Route::post('/report/AccountsReport/getsubs','AccountsReportController@getSubtitles')->name('report.accounts_report_getsubs');
    Route::post('/report/AccountsReport/checkSharing','AccountsReportController@checkSharing')->name('report.checkSharing');
    Route::get('/report/PerAccountsReport','AccountsReportController@index')->name('report.per_accounts_report');
    Route::get('/report/PerAccountsReport2','AccountsReportController@index')->name('report.per_accounts_report2');
    Route::get('/report/AccountableFormsMonthly','AccountsReportController@index')->name('report.montly_accountable_forms');
    Route::post('/report/AccountsReport/', 'AccountsReportController@report')->name('pdf.accounts_report');
    Route::post('/report/AccountsReportDailyShare/', 'AccountsReportController@report')->name('pdf.accounts_report_share');
    Route::post('/report/PerAccountsReport/', 'AccountsReportController@per_acct_report')->name('pdf.per_accounts_report');
    Route::post('/report/PerAccountsReport2/', 'AccountsReportController@per_acct_report2')->name('pdf.per_accounts_report2');
    Route::post('/report/AccountableFormsMonthly','AccountableFormsController@show_monthly')->name('report.montly_accountable_forms');

     Route::post('allow_mnths', 'AccountsReportController@allow_mnths')->name('allow_mnths.collections_deposits');

      /**
     * Cash Division Reports
     */
    Route::get('/cashdiv_report', 'CashDivisionController@cashdiv_daily')->name('report.cashdiv_daily_report');
    Route::post('/cashdiv_report', 'CashDivisionController@cashdiv_report_others')->name('report.cashdiv_report_others');
    Route::post('/cashdiv_delete', 'CashDivisionController@cashdiv_delete')->name('cash_div.delete');
    Route::post('/cashdiv_restore', 'CashDivisionController@cashdiv_restore')->name('cash_div.restore');
    Route::post('/cashdiv/adjustment', 'CashDivisionController@adjustment_add')->name('cashdiv.adjustment_add');
    Route::get('/cashdiv/adjustment/view', 'CashDivisionController@adjustment_view')->name('cashdiv.adjustment_view');
    Route::get('/cashdiv/adjustment/dt', 'CashDivisionController@adjustment_view_dt')->name('cashdiv.adjustment_dt');
    Route::post('/cashdiv/adjustment/update', 'CashDivisionController@adjustment_update')->name('cashdiv.adjustment_update');
    Route::post('/cashdiv/adjustment/delete', 'CashDivisionController@delete_adjustment')->name('cashdiv.adjustment_delete');

    /**
     * Autocomplete X
     */

    Route::post('collection/autocomplete','AutocompleteController@index')->name('collection.autocomplete');
    Route::post('get_serial_current','SerialController@get_serial_current')->name('serial.get_serial_current');

    /*SAND GRAVEL REPORTS*/
     Route::get('/sandgravel_report', 'ReportController@sandgravel_report_municpality')->name('report.sandgravel_report_municpality');

      Route::post('/sandgravel_report', 'ReportController@sandgravel_report_municpality_generate')->name('report.sandgravel_report_municpality_generate');


     Route::get('/col_sandgravel_mnthly', 'SandGravelMonthlyController@index')->name('sandgravel.monthly');

     Route::get('/col_sandgravel_mnthly_create', 'SandGravelMonthlyController@col_sandgravel_mnthly_create')->name('sandgravel.monthly_create');
     Route::post('/col_sandgravel_mnthly_create', 'SandGravelMonthlyController@col_sandgravel_mnthly_store')->name('sandgravel.monthly_save');

     Route::get('/col_sandgravel_mnthly_view/{year}/{month}', 'SandGravelMonthlyController@col_sandgravel_mnthly_view')->name('sandgravel.monthly_view');
     Route::get('/col_sandgravel_mnthly_edit/{year}/{month}', 'SandGravelMonthlyController@col_sandgravel_mnthly_edit')->name('sandgravel.monthly_edit');

      /*Sand Gravel*/

      Route::get('sandgravel_types', 'SandGravelTypes@sandgravel_types')->name('sandgravel.types_clients');
      Route::post('sandgravel_types', 'SandGravelTypes@save_sandgravel_types')->name('sandgravel.types_clientsx');

      Route::post('sandgravel_types_remove', 'SandGravelTypes@save_sandgravel_types_remove')->name('client_type.remove');
      Route::post('sandgravel_types_restore', 'SandGravelTypes@save_sandgravel_types_restore')->name('client_type.restore');
      /* Share on BAC*/
      Route::post('report/shared', 'ReportController@shared_pdf')->name('report.shared_pdf');

      Route::post('clear_other_municpal_fees', 'CertificateController@clear_other_municpal_fees')->name('report.clear_other_municpal_fees');


      /* STAFF REPORTS*/
      Route::get('encoders_report', 'StaffReportsController@encoders_report')->name('staff.encoders_report');
      Route::post('encoders_report', 'StaffReportsController@encoders_report_view')->name('staff.encoders_report_view');

      // Route::get('rererere', 'FieldLandTaxController@rererere')->name('staff.encoders_report');

      /* combine */
        //-- check serial if existing on same transactions/
      Route::post('check_serial_combine', 'AccountController@check_serial_combine')->name('combine.check_serial_combine');
      Route::post('field_land_tax_combine', 'AccountController@field_land_tax_combine')->name('combine.field_land_tax_combine');
      Route::post('field_land_tax_uncombine', 'AccountController@field_land_tax_uncombine')->name('combine.field_land_tax_uncombine');

    Route::get('/report/accountable_forms','AccountableFormsController@index')->name('report.accountable_forms');

    /* formm 56*/
    Route::get('/form56','Form56Controller@index')->name('form56.index');
    Route::post('/form56','Form56Controller@store')->name('form56.store');
    

    Route::get('/form56_view/{id}','Form56Controller@view')->name('form56.view');

     Route::get('/form56_edit/{id}','Form56Controller@edit')->name('form56.edit');
     Route::post('/form56_update/{id}','Form56Controller@update')->name('form56.update');

    # settings_form56
    Route::get('/form56Settings','Form56SettingsController@index')->name('form56_settings.index');
    Route::post('/form56Settings','Form56SettingsController@save')->name('form56_settings.save');

    /* formm 56 end*/
    Route::get('/form56_benedict','Form56Controller@form56_benedict')->name('form56.form56_benedict');
    Route::post('/form56_compute_benedict','Form56ComputeController@form56_compute_benedict')->name('form56.form56_compute_benedict');

        /* client type  */
    Route::get('/client_type_report','ReportController@client_type')->name('report.client_type');
    Route::post('/client_type_report','ReportController@client_type_generate')->name('pdf.client_type_gen');
    Route::post('/client_type_report/clients','ReportController@get_client_report')->name('report.get_clients');
    Route::post('/client_type_report/count_transac', 'ReportController@ctype_count_transac')->name('report.count_transac');

    /*RPT record*/
    Route::get('/customer/rpt_record_get/{id}/{td}/{isPdf?}', 'CustomerController@rpt_record_get')->name('rpt_record_get');
    Route::get('/real_property_tax/payment_records/index', 'CustomerController@rpt_record_index')->name('rpt.records_index');
    Route::get('/real_property_tax/payment_records/dt', 'CustomerController@rpt_record_dt')->name('rpt.records_dt');
    Route::get('/real_property_tax/payment_records/import','CustomerController@importIndex')->name('rpt.import_excel_report');
    Route::post('/real_property_tax/payment_records/import/view','CustomerController@viewExcel')->name('rpt.view_excel_report');
    Route::post('/real_property_tax/payment_records/import/save','CustomerController@saveImportedExcel')->name('rpt.save_excel_report');

    /*RPT delinquents*/
    Route::get('/form56/delinquent_payors', 'Form56Controller@rpt_delinquent')->name('rpt.delinquent');
    Route::get('/form56/delinquent_payors/tbl', 'Form56Controller@delinquents_tbl')->name('rpt.delinquent_tbl');
    Route::get('/form56/delinquent_payors/notice/{mnth}/{yr}/{date?}', 'Form56Controller@generate_notice')->name('rpt.delqnt.print_notice');
    Route::get('/form56/delinquent_payors/view/{id}', 'Form56Controller@view_delinquent')->name('rpt.delqnt_view');
    Route::get('/form56/delinquent_payors/edit/{id}', 'Form56Controller@edit_delinquent')->name('rpt.delqnt_edit');
    Route::post('/form56/delinquent_payors/edit/autofill', 'Form56Controller@edit_delinquent_autofill')->name('rpt.delqnt_edit_autofill');
    Route::post('/form56/delinquent_payors/update', 'Form56Controller@update_arp')->name('rpt.update'); 
    Route::post('/form56/delinquent_payors/rpt_import_excel', 'Form56Controller@rpt_import_excel')->name('rpt.import_excel_delq');
    Route::get('/form56/delinquent_payors/rpt_delq_format/dl', 'Form56Controller@download_delq_format')->name('rpt.dl_rpt_delq');

    /*field land tax update details*/
    Route::post('/field_land_tax/update/details', 'FieldLandTaxController@flt_update_detail')->name('flt.detail_update');


    /* delete booklet */
    Route::get('/receipt/booklet/delete/{id}', 'FieldLandTaxController@delete_booklet')->name('delete.rcpt_booklet');

    Route::get('/form56/get_previous_rcpt', 'Form56Controller@get_previous_rcpt')->name('f56.rcpt_prev');

    // real property display and edit SEF
    Route::get('/report/real_property/prepare', 'PdfController@rpr_report_edit')->name('rpt.prepare');
    Route::post('/report/real_property/submit', 'PdfController@rpt_report_submit')->name('rpt.submit');

    // munisipyo receipts
    Route::get('/municipal_receipts', 'MunicipalReceiptsController@index')->name('mncpal.index');
    Route::post('/municipal_receipts/create', 'MunicipalReceiptsController@new_receipt')->name('mncpal.create');
    Route::get('/municipal_receipts/receipts/dt', 'MunicipalReceiptsController@mncpal_receipts_tbl')->name('mncpal.rcpt.dt');
    Route::get('/municipal_receipts/receipts/view/{id}', 'MunicipalReceiptsController@mncpal_rcpt_view')->name('mncpal.rcpt.view');
    Route::get('/municipal_receipts/receipts/edit/{id}', 'MunicipalReceiptsController@mncpal_rcpt_edit')->name('mncpal.rcpt.edit');
    Route::post('/municipal_receipts/receipts/update', 'MunicipalReceiptsController@mncpal_receipt_update')->name('mncpal.rcpt.update');
    Route::get('/municipal_receipts/receipts/delete/{id}', 'MunicipalReceiptsController@delete_rcpt')->name('mncpal.rcpt.delete');
    Route::get('/municipal_receipts/receipts/certificate/{id}', 'MunicipalReceiptsController@mncpal_cert')->name('mncpal.cert');
    Route::get('/municipal_receipts/receipts/print_certificate/{id}/{gov}/{ppr_size}/{height?}/{width?}', 'MunicipalReceiptsController@mncpal_rcpt_cert')->name('mncpal.print.cert');

    Route::get('/payment_transactions', 'PaymentTransactionsController@index')->name('payment_transactions');
    Route::get('/payment_transactions/store', 'PaymentTransactionsController@store')->name('payment_transactions.store');
});


//