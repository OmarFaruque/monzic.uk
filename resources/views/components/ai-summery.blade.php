<div id="cfw-cart-summary-content">
                    <h3 class="cart_header">
                        YOUR AI DOCUMENT </h3>
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
                                                <div class="dt">Prompt:</div>
                                                <div class="dd">{{ $aiDoc?->prompt }}</div>
                                                <div class="dt">Status:</div>
                                                <div class="dd" style="text-transform:capitalize;">{{ $aiDoc?->status }}</div>
                                               <div class="dt">Currency:</div>
                                                <div class="dd">{{ $aiDoc?->currency }}</div>
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
                                                    class="cpw_subtotal">{{ number_format($aiPrice, 2) }}</span></bdi></span>
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
                                                        class="cpw_total">{{ number_format($aiPrice, 2) }}</span></bdi></span></strong>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </div>

                </div>