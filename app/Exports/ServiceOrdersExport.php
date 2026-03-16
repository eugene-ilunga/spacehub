<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ServiceOrdersExport implements FromCollection, WithHeadings, WithMapping
{
  protected $bookings;

  public function __construct($bookings)
  {
    $this->bookings = $bookings;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->bookings;
  }

  public function headings(): array
  {
    return [
      'Booking No.',
      'Customer Name',
      'Customer Email Address',
      'Space Title',
      'Tax',
      'Total Price',
      'Paid via',
      'Payment Status',
      'Booking Status',
      'Booking Date'
    ];
  }

  /**
   * @var $booking
   */
  public function map($booking): array
  {
    // package price
//    if (is_null($order->package_price)) {
//      $packagePrice = '-';
//    } else {
//      $packagePrice = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->package_price . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');
//    }
//
//    // addon names
//    if (count($order->addonNames) == 0) {
//      $allAddons = '-';
//    } else {
//      $allAddons = '';
//
//      // get the array length
//      $arrLen = count($order->addonNames);
//
//      foreach ($order->addonNames as $key => $addonName) {
//        // checking whether the current index is the last position of the array
//        if (($arrLen - 1) == $key) {
//          $allAddons .= $addonName;
//        } else {
//          $allAddons .= $addonName . ', ';
//        }
//      }
//    }
//
//    // addon price
//    if (is_null($order->addon_price)) {
//      $addonPrice = '-';
//    } else {
//      $addonPrice = ($order->currency_symbol_position == 'left' ? $order->currency_symbol : '') . $order->addon_price . ($order->currency_symbol_position == 'right' ? $order->currency_symbol : '');
//    }
    if (is_null($booking->tax)) {
      $taxPrice = '-';
    } else {
      $taxPrice = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '') . $booking->tax . ($booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '');
    }

    // grand total
    if (is_null($booking->grand_total)) {
      $grandTotal = 'Requested';
    } else {
      $grandTotal = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol : '') . $booking->grand_total . ($booking->currency_symbol_position == 'right' ? $booking->currency_symbol : '');
    }

    // payment status
    if ($booking->payment_status == 'completed') {
      $paymentStatus = 'Completed';
    } else if ($booking->payment_status == 'pending') {
      $paymentStatus = 'Pending';
    } else {
      $paymentStatus = 'Rejected';
    }

    // order status
    if ($booking->booking_status == 'pending') {
      $bookingStatus = 'Pending';
    }
    else if ($booking->booking_status == 'approved') {
      $bookingStatus = 'Approved';
    }
    else {
      $bookingStatus = 'Rejected';
    }

    return [
      '#' . $booking->booking_number,
      $booking->customer_name,
      $booking->customer_email,
      $booking->space_title,
      $taxPrice,
      $grandTotal,
      is_null($booking->payment_method) ? '-' : $booking->payment_method,
      $paymentStatus,
      $bookingStatus,
      Carbon::parse($booking->created_at)->format('M d, Y')
    ];
  }
}
