<?php

declare(strict_types=1);

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = [];

    public function userpath(Company $company)
    {
        return "/$company/reports/{$this->id}";
    }

    public function registry()
    {
        return $this->belongsTo(Registry::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

//    public function setExpiryDateAttribute($value, StoreUserReportRequest $request){
//
//        return $this->attributes['expiry_date'] = Carbon::create(request('report_date'))->addMonths( Registry::where('id', request('registry_id'))->first()->valid_for)->toDateString();
//    }

    public function calculateExpiryDate($report_date)
    {
        return Carbon::create(request('report_date'))->addMonths(Registry::where('id', request('registry_id'))->first()->valid_for)->toDateString();
    }
}
