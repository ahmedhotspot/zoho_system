<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule invoice sync from Zoho Books every day at 12:00 AM
Schedule::command('invoices:sync --queue')
    ->dailyAt('23:59')
    ->timezone('Asia/Riyadh')
    ->name('sync-invoices-from-zoho')
    ->withoutOverlapping()
    ->onSuccess(function () {
        info('Invoice sync scheduled successfully');
    })
    ->onFailure(function () {
        logger()->error('Invoice sync schedule failed');
    });

    // Schedule customer sync from Zoho Books every day at 12:00 AM
    Schedule::command('customers:sync --queue')
        ->dailyAt('23:59')
        ->timezone('Asia/Riyadh')
        ->name('sync-customers-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Customer sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Customer sync schedule failed');
        });

    // Schedule items sync from Zoho Books every day at 12:00 AM
    Schedule::command('items:sync --queue')
        ->dailyAt('23:58')
        ->timezone('Asia/Riyadh')
        ->name('sync-items-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Items sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Items sync schedule failed');
        });

    // Schedule payments sync from Zoho Books every day at 12:00 AM
    Schedule::command('payments:sync --queue')
        ->dailyAt('23:57')
        ->timezone('Asia/Riyadh')
        ->name('sync-payments-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Payments sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Payments sync schedule failed');
        });

    // Schedule estimates sync from Zoho Books every day at 12:00 AM
    Schedule::command('estimates:sync --queue')
        ->dailyAt('23:56')
        ->timezone('Asia/Riyadh')
        ->name('sync-estimates-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Estimates sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Estimates sync schedule failed');
        });

    // Schedule expenses sync from Zoho Books every day at 12:00 AM
    Schedule::command('expenses:sync --queue')
        ->dailyAt('23:55')
        ->timezone('Asia/Riyadh')
        ->name('sync-expenses-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Expenses sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Expenses sync schedule failed');
        });

    // Schedule bills sync from Zoho Books every day at 12:00 AM
    Schedule::command('bills:sync --queue')
        ->dailyAt('23:54')
        ->timezone('Asia/Riyadh')
        ->name('sync-bills-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Bills sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Bills sync schedule failed');
        });

    // Schedule accounts sync from Zoho Books every day at 12:00 AM
    Schedule::command('accounts:sync --queue')
        ->dailyAt('23:53')
        ->timezone('Asia/Riyadh')
        ->name('sync-accounts-from-zoho')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('Accounts sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('Accounts sync schedule failed');
        });

    // ==========================================
    // CRM SCHEDULED JOBS
    // ==========================================

    // Schedule leads sync from Zoho CRM every day at 12:00 AM
    Schedule::command('crm:sync-leads')
        ->dailyAt('23:52')
        ->timezone('Asia/Riyadh')
        ->name('sync-leads-from-zoho-crm')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('CRM Leads sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('CRM Leads sync schedule failed');
        });

    // Schedule contacts sync from Zoho CRM every day at 12:00 AM
    Schedule::command('crm:sync-contacts')
        ->dailyAt('23:51')
        ->timezone('Asia/Riyadh')
        ->name('sync-contacts-from-zoho-crm')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('CRM Contacts sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('CRM Contacts sync schedule failed');
        });

    // Schedule deals sync from Zoho CRM every day at 12:00 AM
    Schedule::command('crm:sync-deals')
        ->dailyAt('23:50')
        ->timezone('Asia/Riyadh')
        ->name('sync-deals-from-zoho-crm')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('CRM Deals sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('CRM Deals sync schedule failed');
        });

    // Schedule accounts sync from Zoho CRM every day at 12:00 AM
    Schedule::command('crm:sync-accounts')
        ->dailyAt('23:49')
        ->timezone('Asia/Riyadh')
        ->name('sync-accounts-from-zoho-crm')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('CRM Accounts sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('CRM Accounts sync schedule failed');
        });

    // Schedule tasks sync from Zoho CRM every day at 12:00 AM
    Schedule::command('crm:sync-tasks')
        ->dailyAt('23:48')
        ->timezone('Asia/Riyadh')
        ->name('sync-tasks-from-zoho-crm')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('CRM Tasks sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('CRM Tasks sync schedule failed');
        });

    // Schedule calls sync from Zoho CRM every day at 12:00 AM
    Schedule::command('crm:sync-calls')
        ->dailyAt('23:47')
        ->timezone('Asia/Riyadh')
        ->name('sync-calls-from-zoho-crm')
        ->withoutOverlapping()
        ->onSuccess(function () {
            info('CRM Calls sync scheduled successfully');
        })
        ->onFailure(function () {
            logger()->error('CRM Calls sync schedule failed');
        });
