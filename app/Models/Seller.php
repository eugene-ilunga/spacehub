<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasFactory;
    protected $fillable = [
        'photo',
        'email',
        'recipient_mail',
        'phone',
        'username',
        'password',
        'status',
        'amount',
        'email_verified_at',
        'avg_rating',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
    ];




  public function spaces()
  {
    return $this->hasMany(Space::class, 'seller_id', 'id');
  }


    public function seller_info()
    {
        return $this->hasOne(SellerInfo::class, 'seller_id', 'id');
    }
    public function seller_infos()
    {
        return $this->hasMany(SellerInfo::class, 'seller_id', 'id');
    }
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'user_id', 'id');
    }
}
