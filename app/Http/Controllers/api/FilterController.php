<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Http\Resources\UserResource;
use App\Shipment;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function filterShipment(Request $request)
    {
        // return 'sdwdww';
        $auth_user = auth('api')->user();
        // dd($start = $request->no_btw['start'] - 1);
        $start = $request->no_btw['start'] - 1;
        if ($auth_user->hasRole('Admin')) {
            if ($request->selectCountry['id'] == 'all') {
                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st6';
                            $shipment_filter = Shipment::where('country_id', $auth_user->country_id)->skip($start)->paginate();
                        } else {
                            // return 'st5';
                            $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                        }

                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st6';
                            $shipment_filter = Shipment::where('country_id', $auth_user->country_id)->skip($start)->where('branch_id', $request->select['id'])->paginate();
                        } else {
                            // return 'st5';
                            $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                        }
                        // return 'st4';
                        // $shipment_filter = Shipment::where('branch_id', $request->select['id'])->where('country_id',$auth_user->country_id)->skip($start)->paginate();
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st1';
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->paginate();

                        } else {
                            // return 'st2';

                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                            } else {
                                $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            }
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st1';
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                        } else {
                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                            } else {
                                $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                            }
                            // return 'st2';
                            // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('country_id',$auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        }
                    }
                }
            } else {

                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st6';
                            $shipment_filter = Shipment::skip($start)->where('country_id', $request->selectCountry['id'])->paginate();
                        } else {
                            // return 'st5';
                            $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->skip($start)->where('country_id', $request->selectCountry['id'])->paginate();
                        }

                    } else {
                        // return 'st4';
                        $shipment_filter = Shipment::where('branch_id', $request->select['id'])->skip($start)->where('country_id', $request->selectCountry['id'])->paginate();
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st1';
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->skip($start)->paginate();
                        } else {
                            // return 'st2';
                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            } else {
                                $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            }
                            // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st1';
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->skip($start)->paginate();
                        } else {
                            // return 'st2';

                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            } else {
                                $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                            }
                            // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        }
                    }
                }
            }
        } else if ($auth_user->hasRole('Client')) {
            // return $request->all();
            if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                if ($request->select['id'] == 'all') {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                        $shipment_filter = Shipment::latest()->skip($start)->where('client_email', $auth_user->email)->take(500)->get();
                    } else {
                        // return 'st5';
                        $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->get();
                    }

                } else {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                        $shipment_filter = Shipment::latest()->skip($start)->where('branch_id', $request->select['id'])->where('client_email', $auth_user->email)->where('client_email', $auth_user->email)->take(500)->get();
                    } else {
                        // return 'st5';
                        $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->where('client_email', $auth_user->email)->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->get();
                    }
                    // return 'st4';
                    // $shipment_filter = Shipment::where('branch_id', $request->select['id'])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->get();
                }
            } else {
                if ($request->select['id'] == 'all') {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st1';
                        $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->get();
                    } else {
                        // return 'st2';
                        if ($request->selectStatus['name'] == 'Delivered') {
                            $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                        } elseif ($request->selectStatus['name'] == 'Dispatched') {
                            $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                        } else {
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                        }
                        // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                    }
                } else {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st1';
                        $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->get();
                    } else {
                        // return 'st2';

                        if ($request->selectStatus['name'] == 'Delivered') {
                            $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                        } elseif ($request->selectStatus['name'] == 'Dispatched') {
                            $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                        } else {
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                        }
                        // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('client_email', $auth_user->email)->where('branch_id', $request->select['id'])->latest()->skip($start)->take(500)->where('status', $request->selectStatus['name'])->get();
                    }
                }
            }
        }


        elseif ($auth_user->hasPermissionTo('Filter Shipments')) {
            // return $request->all();
            if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                if ($request->select['id'] == 'all') {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                        $shipment_filter = Shipment::skip($start)->where('country_id', $auth_user->country_id)->paginate();
                    } else {
                        // return 'st5';
                        $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                    }

                } else {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st6';
                        $shipment_filter = Shipment::skip($start)->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->where('country_id', $auth_user->country_id)->paginate();
                    } else {
                        // return 'st5';
                        $shipment_filter = Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                    }
                    // return 'st4';
                    // $shipment_filter = Shipment::where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                }
            } else {
                if ($request->select['id'] == 'all') {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st1';
                        $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->paginate();
                    } else {
                        // return 'st2';
                        if ($request->selectStatus['name'] == 'Delivered') {
                            $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        } elseif ($request->selectStatus['name'] == 'Dispatched') {
                            $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        } else {
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        }
                        // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                    }
                } else {
                    if ($request->selectStatus['name'] == 'All') {
                        // return 'st1';
                        $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->skip($start)->paginate();
                    } else {
                        // return 'st2';

                        if ($request->selectStatus['name'] == 'Delivered') {
                            $shipment_filter = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        } elseif ($request->selectStatus['name'] == 'Dispatched') {
                            $shipment_filter = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        } else {
                            $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                        }
                        // $shipment_filter = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->skip($start)->where('status', $request->selectStatus['name'])->paginate();
                    }
                }
            }
        }
        // $multiplied = $shipment_filter->transform(function ($item, $key) {
        //     if (!empty($item->statuses)) {
        //         foreach ($item->statuses as $status) {
        //             $user = User::find($status->id);
        //             $item->user_name = $user->name;
        //             var_dump($user->name);die;

        //         }
        //     }
        //     return $item;
        // });

        return ShipmentResource::collection($shipment_filter);
        // return $shipment_filter;
    }

    public function filterCount(Request $request)
    {
        // return $request->all();
        $auth_user = auth('api')->user();
        // dd($start = $request->no_btw['start'] - 1);
        $start = $request->no_btw['start'] - 1;
        if ($auth_user->hasRole('Admin')) {
            if ($request->selectCountry['id'] == 'all') {
                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // return 'st6';
                            $shipments = Shipment::count();
                        } else {
                            // $shipments = 'st5';
                            $shipments = Shipment::where('status', $request->selectStatus['name'])->count();
                        }

                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            // $shipments = 'st6';
                            $shipments = Shipment::where('branch_id', $request->select['id'])->count();
                        } else {
                            // $shipments = 'st5';
                            $shipments = Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->count();
                        }
                        // $shipments = 'st4';
                        // $shipments = Shipment::where('branch_id', $request->select['id'])->count();
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
                        } else {
                            // $shipments = 'st2';
                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipments = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['name'])->count();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipments = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['name'])->count();
                            } else {
                                $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['name'])->count();
                            }
                            // $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['name'])->count();
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            // $shipments = 'st1';
                            $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->count();
                        } else {
                            // $shipments = 'st2';
                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipments = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipments = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                            } else {
                                $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                            }
                            // $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                        }
                    }
                }
            } else {

                if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // $shipments = 'st6';
                            $shipments = Shipment::where('country_id', $request->selectCountry['id'])->count();
                        } else {
                            // $shipments = 'st5';
                            $shipments = Shipment::where('status', $request->selectStatus['name'])->where('country_id', $request->selectCountry['id'])->count();
                        }

                    } else {
                        // $shipments = 'st4';
                        $shipments = Shipment::where('branch_id', $request->select['id'])->where('country_id', $request->selectCountry['id'])->count();
                    }
                } else {
                    if ($request->select['id'] == 'all') {
                        if ($request->selectStatus['name'] == 'All') {
                            // $shipments = 'st1';
                            $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->count();
                        } else {
                            // $shipments = 'st2';
                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipments = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('status', $request->selectStatus['name'])->count();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipments = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('status', $request->selectStatus['name'])->count();
                            } else {
                                $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('status', $request->selectStatus['name'])->count();
                            }
                            // $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('status', $request->selectStatus['name'])->count();
                        }
                    } else {
                        if ($request->selectStatus['name'] == 'All') {
                            // $shipments = 'st1';
                            $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                        } else {
                            // $shipments = 'st2';
                            if ($request->selectStatus['name'] == 'Delivered') {
                                $shipments = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                            } elseif ($request->selectStatus['name'] == 'Dispatched') {
                                $shipments = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                            } else {
                                $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                            }
                            // $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $request->selectCountry['id'])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                        }
                    }
                }
            }

        } elseif ($auth_user->hasPermissionTo('Filter Shipments')) {
            // $shipments = $request->all();
            if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
                if ($request->select['id'] == 'all') {
                    if ($request->selectStatus['name'] == 'All') {
                        // $shipments = 'st6';
                        $shipments = Shipment::where('country_id', $auth_user->country_id)->count();
                    } else {
                        // $shipments = 'st5';
                        $shipments = Shipment::where('status', $request->selectStatus['name'])->where('country_id', $auth_user->country_id)->count();
                    }

                } else {
                    if ($request->selectStatus['name'] == 'All') {
                        // $shipments = 'st6';
                        $shipments = Shipment::where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->where('country_id', $auth_user->country_id)->count();
                    } else {
                        // $shipments = 'st5';
                        $shipments = Shipment::where('status', $request->selectStatus['name'])->where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->where('country_id', $auth_user->country_id)->count();
                    }
                    // $shipments = 'st4';
                    // $shipments = Shipment::where('branch_id', $request->select['id'])->where('country_id', $auth_user->country_id)->count();
                }
            } else {
                if ($request->select['id'] == 'all') {
                    if ($request->selectStatus['name'] == 'All') {
                        // $shipments = 'st1';
                        $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->count();
                    } else {
                        // $shipments = 'st2';
                        if ($request->selectStatus['name'] == 'Delivered') {
                            $shipments = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('status', $request->selectStatus['name'])->count();
                        } elseif ($request->selectStatus['name'] == 'Dispatched') {
                            $shipments = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('status', $request->selectStatus['name'])->count();
                        } else {
                            $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('status', $request->selectStatus['name'])->count();
                        }
                        // $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('status', $request->selectStatus['name'])->count();
                    }
                } else {
                    if ($request->selectStatus['name'] == 'All') {
                        // $shipments = 'st1';
                        $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->count();
                    } else {
                        // $shipments = 'st2';
                        if ($request->selectStatus['name'] == 'Delivered') {
                            $shipments = Shipment::whereBetween('derivery_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                        } elseif ($request->selectStatus['name'] == 'Dispatched') {
                            $shipments = Shipment::whereBetween('dispatch_date', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                        } else {
                            $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                        }
                        // $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('country_id', $auth_user->country_id)->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['name'])->count();
                    }
                }
            }
        }
        return ShipmentResource::collection($shipments);

    }

    public function getDeriveredS(Request $request)
    {
        if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments = 'st6';
                    $shipments = Shipment::where('status', 'Delivered')->count();
                } else {
                    // return 'st5';
                    $shipment = Shipment::where('status', 'Delivered')->count();
                }

            } else {
                // $shipment = 'st4';
                $shipment = Shipment::where('status', 'Delivered')->where('branch_id', $request->select['id'])->count();
            }
        } else {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipment = 'st1';
                    $shipment = Shipment::where('status', 'Delivered')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
                } else {
                    // $shipment = 'st2';
                    $shipment = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', 'Delivered')->count();
                }
            } else {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipment = 'st1';
                    $shipment = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Delivered')->count();
                } else {
                    // $shipment = 'st2';
                    $shipment = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Delivered')->count();
                }
                // $shipment = 'st3';
                // $shipment = Shipment::where('branch_id', $request->select['id'])
                //                 // ->where('status', 'Delivered')
                //                 ->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
                //                 ->count();
            }
        }
        return ShipmentResource::collection($shipment);
    }

    public function getOrdersS(Request $request)
    {
        // return $request->all();
        if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // return 'st6';
                    $shipments = Shipment::count();
                } else {
                    // $shipments = 'st5';
                    $shipments = Shipment::count();
                }

            } else {
                // $shipments = 'st4';
                $shipments = Shipment::where('branch_id', $request->select['id'])->count();
            }
        } else {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments = 'st1';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
                } else {
                    // $shipments = 'st2';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
                }
            } else {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments = 'st1';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->count();
                } else {
                    // $shipments = 'st2';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->count();
                }
                // $shipments = Shipment::where('branch_id', $request->select['id'])
                //                 //
                //                 ->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
                //                 ->count();
            }
        }
        return ShipmentResource::collection($shipments);
    }

    public function getPendingS(Request $request)
    {
        if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments = 'st6';
                    $shipments = Shipment::where('status', '!=', 'Delivered')->count();
                } else {
                    // $shipments = 'st5';
                    $shipments = Shipment::where('status', '!=', 'Delivered')->count();
                }

            } else {
                // $shipments = 'st4';
                $shipments = Shipment::where('status', '!=', 'Delivered')->where('branch_id', $request->select['id'])->count();
            }
        } else {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments = 'st1';
                    $shipments = Shipment::where('status', '!=', 'Delivered')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
                } else {
                    // $shipments = 'st2';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', '!=', 'Delivered')->count();
                }
            } else {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments = 'st1';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', '!=', 'Delivered')->count();
                } else {
                    // $shipments = 'st2';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', '!=', 'Delivered')->count();
                }
                // $shipments = 'st3';
                // $shipments = Shipment::where('branch_id', $request->select['id'])
                //                 // ->where('status', '!=', 'Delivered')
                //                 ->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
                //                 ->count();
            }
        }
        return ShipmentResource::collection($shipments);
    }

    public function filterPayment(Request $request)
    {
        if ($request->abbr == 'All') {
            $usersAll = User::paginate();
            $userArr = [];
            foreach ($usersAll as $userAll) {
                if ($userAll->hasRole('Client')) {
                    $userArr[] = $userAll;
                }
            }
        } else {
            $users = User::where('payment_status', $request->abbr)->paginate();
            // return $user->hasRole('Client');
            $userArr = [];
            foreach ($users as $user) {
                if ($user->hasRole('Client')) {
                    $userArr[] = $user;
                }
            }
        }
        return UserResource::collection($userArr);

    }

    public function getreturned(Request $request)
    {
        if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // return 'st6';
                    $shipments = Shipment::where('status', 'Returned')->count();
                } else {
                    // $shipments =  'st5';
                    $shipments = Shipment::where('status', 'Returned')->count();
                }

            } else {
                // $shipments =  'st4';
                $shipments = Shipment::where('status', 'Returned')->where('branch_id', $request->select['id'])->count();
            }
        } else {
            if ($request->select['id'] == 'all') {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments =  'st1';
                    $shipments = Shipment::where('status', 'Returned')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->count();
                } else {
                    // $shipments =  'st2';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', 'Returned')->count();
                }
            } else {
                if ($request->selectStatus['name'] == 'All') {
                    // $shipments =  'st1';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Returned')->count();
                } else {
                    // $shipments =  'st2';
                    $shipments = Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', 'Returned')->count();
                }
                // $shipments =  'st3';
                // $shipments =  Shipment::where('branch_id', $request->select['id'])
                //                 // ->where('status', 'Returned')
                //                 ->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
                //                 ->count();
            }
        }
        return ShipmentResource::collection($shipments);
    }

    public function glSearch(Request $request)
    {
        // return $request->all();
        $search = $request->search;
        $shipments = Shipment::where('bar_code', 'LIKE', "%{$search}%")
            ->orwhere('client_phone', 'LIKE', "%{$search}%")
            ->orwhere('client_email', 'LIKE', "%{$search}%")
            ->orwhere('client_name', 'LIKE', "%{$search}%")->paginate();
        return ShipmentResource::collection($shipments);
    }

}
