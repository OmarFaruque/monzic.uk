<div id="cfw-cart-summary-content">
                    <h3 class="cart_header">
                        YOUR QUOTE </h3>
                    <hr>
                    <div class="d-block d-md-none mb-3"><a class="view_summ_action">View Summary Details <i
                                class="fa fa-caret-down"></i></a></div>

                    <div id="cfw-checkout-before-order-review"></div>
                    <div class="d-none d-md-block  quotation_summ">
                        <table id="cfw-cart" class="cfw-module">
                            <tbody>
                                <tr class="cart-item-row cart-item-f4bc63535943868b6eab0ed53bff19e0 cart_item">



                                    <th class="cfw-cart-item-description">

                                        <div class="cfw-cart-item-data">
                                            <div class="variationx">
                                                <div class="dt">Registration Number:</div>
                                                <div class="dd">{{ $quote?->reg_number }}</div>
                                                <div class="dt">Vehicle Make:</div>
                                                <div class="dd">{{ $quote?->vehicle_make }}</div>
                                                <div class="dt">Vehicle Model:</div>
                                                <div class="dd">{{ $quote?->vehicle_model }}</div>
                                                <div class="dt">Engine CC:</div>
                                                <div class="dd">{{ $quote?->engine_cc }}</div>
                                                <div class="dt">Start Date:</div>
                                                <div class="dd">{{ date('d-m-Y', strtotime($quote?->start_date)) }}
                                                </div>
                                                <div class="dt">Start Time:</div>
                                                <div class="dd">{{ date('h:m a', strtotime($quote?->start_date .' '.$quote?->start_time)) }}</div>
                                                <div class="dt">End Date:</div>
                                                <div class="dd">{{ date('d-m-Y', strtotime($quote?->end_date)) }}</div>
                                                <div class="dt">End Time:</div>
                                                <div class="dd">{{ date('h:m a', strtotime($quote?->end_date .' '.$quote?->end_time))  }}</div>
                                                <div class="dt">Date of Birth:</div>
                                                <div class="dd">{{ date('d-m-Y', strtotime($quote?->date_of_birth)) }}
                                                </div>
                                                <div class="dt">Reason:</div>
                                                <div class="dd">{{ $quote?->cover_reason }}</div>
                                                <div class="dt">Name(s):</div>
                                                <div class="dd">{{ $quote?->title }} {{ $quote?->first_name }} {{ $quote?->middle_name }} {{ $quote?->last_name }}</div>
                                                <div class="dt">Address:</div>
                                                <div class="dd">{{ $quote?->address }}</div>
                                                <div class="dt">Postcode:</div>
                                                <div class="dd">{{ $quote?->postcode }}</div>
                                                <div class="dt">Occupation:</div>
                                                <div class="dd">{{ $quote?->occupation }}</div>
                                                <div class="dt">Licence Type:</div>
                                                <div class="dd">{{ $quote?->licence_type }}</div>
                                                <div class="dt">Licence Held Duration:</div>
                                                <div class="dd">{{ $quote?->licence_held_duration }}</div>
                                                <div class="dt">Vehicle Value:</div>
                                                <div class="dd">{{ $quote?->vehicle_type }}</div>
                                            </div>
                                        </div>
                                        <div class="cfw_cart_item_after_data">
                                        </div>
                                    </th>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="cfw-totals-list" class="cfw-module d-none d-md-block  quotation_summ">
                        <table class="cfw-module table">

                            <tbody>
                                <tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td><span class="woocommerce-Price-amount amount"><bdi><span
                                                    class="woocommerce-Price-currencySymbol">£</span><span
                                                    class="cpw_subtotal">{{ number_format($quote?->cpw, 2) }}</span></bdi></span>
                                    </td>
                                </tr>
                                <tr class="cart-discount d-none">
                                    <th>Discount</th>
                                    <td><span class="woocommerce-Price-amount amount"><bdi><span
                                                    class="woocommerce-Price-currencySymbol">£</span><span
                                                    class="cpw_discount"></span></bdi></span>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Total</th>
                                    <td><strong><span class="woocommerce-Price-amount amount"><bdi><span
                                                        class="woocommerce-Price-currencySymbol">£</span><span
                                                        class="cpw_total">{{ number_format($quote?->cpw, 2) }}</span></bdi></span></strong>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                </div>