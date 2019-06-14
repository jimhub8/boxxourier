<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Facades\Auth;
use App\User;

class FinanceController extends Controller
{
    public function payment(Request $request, $id)
    {
        // return $request->all();
        $user = User::find($id);
        $user->payment_status = $request->payment;
        $user->save();
        return $user;
    }
    public function filterFin(Request $request)
    {
        // return $request->all();
        $user = Auth::user();
        // Client Start
        if ($user->hasRole('Client')) {
            if ($request->selectCountry['id'] == 'all') {   
                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
				// return 'st6';
                                return Shipment::latest()->where('client_id', Auth::id())->take(500)->get();
                            } else {
                                return Shipment::latest()->where('client_id', Auth::id())->take(500)->where('paid', $request->selectPay['paid'])->get();
                            }
                        } else {
				// return 'st5';
                            return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->latest()->take(500)->get();
                        }

                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                            if ($request->selectPay['status'] == 'All') {
                            // return 'st6';
                                return Shipment::latest()->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->take(500)->get();
                            } else {
                                return Shipment::latest()->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        } else {

                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->latest()->take(500)->get();
                            } else {
                                return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->where('paid', $request->selectPay['paid'])->latest()->take(500)->get();
                            }
						// return 'st5';
                        }
				// return 'st4';
					// return Shipment::where('branch_id', $request->select['id'])->where('client_id', Auth::id())->latest()->take(500)->get();
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->latest()->take(500)->get();
                            }
				// return 'st1';
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
				// return 'st2';
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
                            if ($request->selectPay['status'] == 'All') {
                    // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->latest()->take(500)->get();
                            }
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->where('status', $request->selectStatus['name'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
                        }
                    }
                }
            } else {

                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                // return 'st6';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::latest()->where('client_id', Auth::id())->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::latest()->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            }
                        } else {
				// return 'st5';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->latest()->where('country_id', $request->selectCountry['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        }

                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::where('branch_id', $request->select['id'])->where('client_id', Auth::id())->latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::where('branch_id', $request->select['id'])->where('client_id', Auth::id())->latest()->where('country_id', $request->selectCountry['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        } else {
                        // return 'st5';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::where('status', $request->selectStatus['name'])->where('client_id', Auth::id())->where('branch_id', $request->select['id'])->latest()->where('country_id', $request->selectCountry['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        }
        
				// return 'st4';
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('country_id', $request->selectCountry['id'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->latest()->take(500)->get();
                            }
				// return 'st1';
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('country_id', $request->selectCountry['id'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
				// return 'st2';
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                            }
				// return 'st1';
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_id', Auth::id())->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('paid', $request->selectPay['paid'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
                        }
                    }
                }
            }
        }
        // Client End

        

        // Finance
        elseif ($user->can('view finance')) {
            if ($request->selectCountry['id'] == 'all') {
                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
				// return 'st6';
                                return Shipment::latest()->take(500)->get();
                            } else {
                                return Shipment::latest()->take(500)->where('paid', $request->selectPay['paid'])->get();
                            }
                        } else {
				// return 'st5';
                            return Shipment::where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                        }

                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                            if ($request->selectPay['status'] == 'All') {
                            // return 'st6';
                                return Shipment::latest()->where('branch_id', $request->select['id'])->take(500)->get();
                            } else {
                                return Shipment::latest()->where('branch_id', $request->select['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        } else {

                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->take(500)->get();
                            } else {
                                return Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->where('paid', $request->selectPay['paid'])->latest()->take(500)->get();
                            }
						// return 'st5';
                        }
				// return 'st4';
					// return Shipment::where('branch_id', $request->select['id'])->latest()->take(500)->get();
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->latest()->take(500)->get();
                            }
				// return 'st1';
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
				// return 'st2';
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
				// return 'st1';
                            if ($request->selectPay['status'] == 'All') {
                    // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->latest()->take(500)->get();
                            }
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                // return 'st6';
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->where('status', $request->selectStatus['name'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
                        }
                    }
                }
            } else {

                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                // return 'st6';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::latest()->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            }
                        } else {
				// return 'st5';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::where('status', $request->selectStatus['name'])->latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::where('status', $request->selectStatus['name'])->latest()->where('country_id', $request->selectCountry['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        }

                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::where('branch_id', $request->select['id'])->latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::where('branch_id', $request->select['id'])->latest()->where('country_id', $request->selectCountry['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        } else {
                        // return 'st5';
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->where('country_id', $request->selectCountry['id'])->take(500)->get();
                            } else {
                                return Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->latest()->where('country_id', $request->selectCountry['id'])->where('paid', $request->selectPay['paid'])->take(500)->get();
                            }
                        }
        
				// return 'st4';
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->latest()->take(500)->get();
                            }
				// return 'st1';
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
				// return 'st2';
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('paid', $request->selectPay['paid'])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->latest()->take(500)->get();
                            }
				// return 'st1';
                        } else {
                            if ($request->selectPay['status'] == 'All') {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            } else {
                                return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('paid', $request->selectPay['paid'])->latest()->take(500)->where('status', $request->selectStatus['name'])->get();
                            }
                        }
                    }
                }
            }
        } else {
            return;
        }

    }
}
