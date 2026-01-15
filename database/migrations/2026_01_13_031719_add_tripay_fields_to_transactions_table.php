<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_type')->default('cash')->after('payment_method'); // cash or online
            $table->string('tripay_reference')->nullable()->after('payment_type');
            $table->string('tripay_merchant_ref')->nullable()->after('tripay_reference');
            $table->string('tripay_payment_method')->nullable()->after('tripay_merchant_ref');
            $table->string('tripay_payment_name')->nullable()->after('tripay_payment_method');
            $table->string('payment_status')->default('pending')->after('tripay_payment_name'); // pending, paid, failed, expired
            $table->text('tripay_checkout_url')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('tripay_checkout_url');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'tripay_reference',
                'tripay_merchant_ref',
                'tripay_payment_method',
                'tripay_payment_name',
                'payment_status',
                'tripay_checkout_url',
                'paid_at'
            ]);
        });
    }
};
