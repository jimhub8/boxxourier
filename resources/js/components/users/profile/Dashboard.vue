<template>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-12">

                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <img class="avatar" :src="user.profile"
                            width="30px"> Welcome {{ user.name }}!
                        </div>
                        <div class="panel-body numbers" style="padding-top:20px;background: url('storage/usa.gif') no-repeat right;background-size: contain;">
                            <h5 style="    font-family: inherit;"><b
                                style="border: 1px solid #a1b3cb;color: #285189;padding: 5px 10px;border-radius: 2px;line-height: 50px;"><img
                                    style="margin-top: -4px;"
                                    src="/storage/icons/kenya.jpg" width="20px">
                                Your Shipto Address</b>
                                <br>{{ user.name }}, <br>
                                <a href="dashboard/profile" style="font-size:13px;"><i class="ti-mouse-alt"></i> Choose your
                                address</a>

                            </h5>
                        </div>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Account Summary
                        </div>
                        <div class="panel-body numbers row" style="padding-top:20px;">
                            <div class="col-md-6">
                                <strong>TOTAL ORDERS</strong><br>
                                <span>{{ Allshipments }}<a href="/dashboard/orders" title="view details"> </a></span><br>
                                <strong>IN TRANSIT</strong><br>
                                <span>{{ getPedding }}<a href="/dashboard/orders" title="view details"> </a></span><br>
                                <strong>PAYMENT PENDING</strong><br>
                                <span>{{ AllScheduled }}<a href="#" title="view details"> </a></span><br>
                                </div>
                                <div class="col-md-6">
                                    <strong>Delivered</strong><br>
                                    <span>{{ AllderiveredShipment }}<a href="/dashboard/orders" title="view details"> </a></span><br>
                                    <strong>PAID</strong><br>
                                    <span>0 KSH<a href="/dashboard/orders" title="view details"> </a></span><br>
                                    <strong>DUE PAYMENTS</strong><br>
                                    <span>0 KSH<a href="/dashboard/orders" title="view details"> </a></span><br>
                                </div>

                                </div>
                            </div>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Recent Orders
                                </div>
                                <div class="panel-body" style="padding-top:20px;max-height: 325px;overflow-y: scroll;overflow-x: hidden;">
                                    <div id="datatable1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">

                                        <div class="row">
                                            <div class="col-sm-12 table-responsive">
                                                <table id="datatable1" class="table table-striped table-hover dataTable no-footer" role="grid" aria-describedby="datatable1_info">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Order ID</th>
                                                            <th>Title</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-center mt40 col-sm-12">
                                                <router-link to="/shipments" class="btn btn-yellow">View all orders</router-link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end of widget-->
                    </div>
                    <div class="col-md-5 col-sm-6">

                        <div class="widget">
                            <!-- <a href="dashboard/orders/create" class="btn btn-color"><i class="ti-gift icon"></i> CREATE A PRE-ALERT</a> <a href="dashboard/orders/" class="btn"><i class="fas fa-cube"></i> VIEW ALL ORDERS</a> -->
                            <router-link to="/shipments" class="btn"><i class="fas fa-cube"></i>VIEW ALL ORDERS</router-link>

                            <h6 class="title">Estimate the cost of shipping</h6>
                            <hr>
                            <div id="divFormCalculator" class="calcu-form-background">
                                <div class="row">
                                    <div class="col-xs-12">

                                        <label class="calculato_label" style="padding-top:3px">Shipping To</label>

                                        <select name="ship_to" id="ship_to" class="ilong">
                                <option value="106">Kenya</option>
                            </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label class="calculato_label_new">Estimated Weight</label>

                                        <input type="text" name="weight_value" value="" id="weight_value" size="5" maxlength="6">
                                    </div>
                                        <div class="col-xs-6">
                                            <label>Scale</label>
                                            <select name="weight_type" id="weight_type">
                                <option value="1">kgs</option>
                            </select>
                                        </div>
                                    </div>

                                    <!-- <div class="row">
                <div class="col-xs-3">
                    <label>Length</label>
                    <input type="text" name="product_length" value="" id="product_length" size="5">
                    </div><div class="col-xs-3">
                    <label>Width</label>
                    <input type="text" name="product_width" value="" id="product_width" size="5">
                         </div><div class="col-xs-3">
                    <label>Height</label>
                    <input type="text" name="product_depth" value="" id="product_depth" size="5">
                      </div><div class="col-xs-3">
                      <label>Scale</label>
                    <select name="dimension_type" id="dimension_type"><option value="1">inch</option></select>
                     </div>
                     </div>-->
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="calculato_label" style="font-size:14px;">Purchase Details</div>

                                            <label class="calculato_label_new" style="padding-top:3px;">Item Invoice Amount
                                (KSH)</label>

                                            <input type="text" name="product_cost" value="" id="product_cost" size="30" maxlength="6">
                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">

                                                <label class="calculato_label_new">Product Name</label>

                                                <select id="product_name" name="product_name">
                                <option selected="selected" value="">Select Product Name</option>
                                <option value="22.3">A.C Part</option>
                                <option value="37.3">A.C Unit</option>
                                <option value="32.3">Adhesives (glue)</option>
                                <option value="17.3">Aluminum</option>
                                <option value="37.3">Aluminum (kitchen or household)</option>
                                <option value="37.3">Audio equipment</option>
                                <option value="22.3">Audio equipment (parts)</option>
                                <option value="40.8">Auto Part</option>
                            </select>

                                            </div>
                                        </div>

                                        <div class="col-xs-6" style="padding-left: 0;">
                                            <button id="btnCalculate" type="submit"
                            class="btn btn-yellow ladda-button center-block btn-stBlue"><span
                                class="ladda-label">Calculate </span></button>
                                        </div>

                                    </div>

                                </div>

                                <div class="result" id="result" style="display:none;">

                                    <div class="panel panel-info">
                                        <div class="panel-heading" style="background-color:#153f5f;">
                                            <h3 class="panel-title" align="center" style="text-align:left;color:#fff;">Estimated Cost</h3>
                                        </div>
                                        <div class="panel-body" style="padding-top:20px;">
                                            <div class="row">

                                                <div class="col-md-12 col-lg-12 table-responsive">
                                                    <table class="table table-user-information">
                                                        <tbody>

                                                            <tr>
                                                                <td>Estimated Shipping Weight</td>
                                                                <td><span id="sweight"></span> kgs</td>
                                                            </tr>
                                                            <!--<tr>
                        <td>Dimensional Weight</td>
                        <td><span id="sdweight"></span> kgs</td>
                      </tr>-->

                                                            <tr>
                                                                <td>Transport Charges</td>
                                                                <td>KSH<span id="rate"></span></td>
                                                            </tr>

                                                            <tr>
                                                                <td>Customs Fees</td>
                                                                <td>KSH<span id="Customs"></span></td>
                                                            </tr>

                                                            <tr>
                                                                <td>Processing Fee</td>
                                                                <td>KSH<span id="processing_fee"></span></td>
                                                            </tr>

                                                            <tr>
                                                                <td>General Consumption Tax </td>
                                                                <td>KSH<span id="GCT"></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Total (KSH)</strong></td>
                                                                <td><strong> KSH<span id="final_rate">0.00</span></strong></td>

                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                    <p align="center">
                                                        <a href="#" class="btn btn-success">Signup</a>

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-footer">

                                            <span>*The price indicated above is only an estimate of the freight and customs charges. We
                            remind you that the final costs may vary depending on the actual customs declarations and/or
                            other charges such as special handling on consumer commodities and a select number of import
                            requirements on certain items (see restricted shipments for more information).</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
</template>

<script>
export default {
    props: ["user"],
    data() {
        return {
            label: [],
            data: [],
            Allshipments: {},
            AllScheduled: {},
            notCompleted: {},
            AllCanceled: {},
            AllderiveredShipment: {},
            AllPending: null,
            AllDelivered: null
        }
    },
    methods: {
      
    },
    mounted() {
        
        axios.get('/customerDelivered')
            .then((response) => {
                this.AllDelivered = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/customerCount')
            .then((response) => {
                this.Allshipments = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/customerScheduled')
            .then((response) => {
                this.AllScheduled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/customerCanceled')
            .then((response) => {
                this.AllCanceled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/customerDelivered')
            .then((response) => {
                this.AllderiveredShipment = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
    computed: {
        getPedding() {
            return (parseInt(this.Allshipments) - parseInt(this.AllScheduled) - parseInt(this.AllDelivered))
        }
    },
};
</script>

<style scoped>
.numbers span {
    font-size: 20px;
    line-height: 30px;
    color: #009688;
}

.panel-body::-webkit-scrollbar-track {
    border-radius: 10px;
    background-color: #fff;
}

.panel-body::-webkit-scrollbar {
    width: 10px;
    background-color: #fff;
}

.panel-body::-webkit-scrollbar-thumb {
    border-radius: 10px;

    background-color: #737d90;
}

.btn-color {
    background: #4caf50;
    color: #fff !important;
}

input[type="text"],
input[type="email"],
input[type="number"],
textarea,
select,
input[type="password"] {
    background: rgb(255, 255, 255);
    border-radius: 3px;
    box-shadow: none;
    border: 1px solid #dcdcdc;
    width: 100%;
    font-family: inherit;
    height: 40px;
    padding-left: 20px;
    color: #607D8B;
    font-weight: 800 !important;
    margin-bottom: 15px;
}

input[type="text"]:hover,
input[type="text"]:focus,
input[type="text"]:active,
input[type="email"]:hover,
input[type="email"]:focus,
input[type="email"]:active,
input[type="number"]:hover,
input[type="number"]:focus,
input[type="number"]:active,
select:hover,
select:focus,
select:active,
textarea:hover,
textarea:focus,
textarea:active,
input[type="password"]:hover,
input[type="password"]:active,
input[type="password"]:focus {
    outline: none;
    border: 1px solid #dcdcdc;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.125);
}

h1,
h2,
h3,
h4,
h5,
h6,
p,
ul,
ol,
pre,
table,
blockquote,
input,
button,
select,
textarea {
    margin-bottom: 24px;
    margin-top: 0;
    padding: 0;
}

.widget-handle .search-form input {
    margin: 0;
    font-size: 16px;
}

input,
button,
select,
textarea {
    font-family: "Raleway", "Helvetica Neue", Helvetica, Arial, sans-serif;
}

input[type="text"],
button,
textarea,
select,
input[type="password"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.bg-secondary input[type="text"],
.bg-secondary textarea,
.bg-secondary select {
    background: #fff;
}

.input-lh {
    line-height: 50px;
}

.attempted-submit .field-error {
    outline: 1px red !important;
}

.input-with-label span {
    font-family: "Raleway", "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 11px;
    text-transform: uppercase;
    color: #292929;
    letter-spacing: 1px;
    font-weight: 700;
    display: block;
    cursor: default;
}

.image-bg.bg-light input.transparent,
.image-bg.bg-light button.transparent {
    border-color: #292929;
}

input.transparent,
button.transparent {
    background: none;
    border: 1px solid rgba(255, 255, 255, 0.5);
    color: #fff !important;
}

::-webkit-input-placeholder {
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 1px;
    color: #777;
    font-size: 11px;
}

:-moz-placeholder {
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 1px;
    color: #777;
    font-size: 11px;
}

::-moz-placeholder {
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 1px;
    color: #777;
    font-size: 11px;
}

:-ms-input-placeholder {
    text-transform: uppercase;
    font-weight: bold;
    letter-spacing: 1px;
    color: #777;
    font-size: 11px;
}

input.transparent::-webkit-input-placeholder,
button.transparent::-webkit-input-placeholder {
    color: #fff;
}

input.transparent::-moz-input-placeholder,
button.transparent::-moz-input-placeholder {
    color: #fff;
}

input.transparent:-moz-input-placeholder,
button.transparent:-moz-input-placeholder {
    color: #fff;
}

input.transparent:-ms-input-placeholder,
button.transparent:-ms-input-placeholder {
    color: #fff;
}

input[type="submit"],
button[type="submit"] {
    -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .05);
    box-shadow: 0 1px 0 rgba(0, 0, 0, .05);
    background-color: #3f51b5;
    background-image: -webkit-linear-gradient(top, transparent, transparent);
    background-image: linear-gradient(top, transparent, transparent);
    border: 1px solid transparent;
    color: #fff;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    cursor: default;
    cursor: pointer;
    width: 100%;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    white-space: nowrap;
    margin-right: 16px;
    height: 40px;
    line-height: 28px;
    min-width: 54px;
    outline: 0;
    padding: 0 15px;
}

input[type="submit"]:hover,
button[type="submit"]:hover,
input[type="submit"]:active,
button[type="submit"]:active {
    -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .05);
    box-shadow: 0 1px 0 rgba(0, 0, 0, .05);
    background-color: #3f51b5;
    background-image: -webkit-linear-gradient(top, transparent, transparent);
    background-image: linear-gradient(top, transparent, transparent);
    border: 1px solid transparent;
    color: #fff;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    cursor: default;
    font-size: 12px;
    font-weight: bold;
    text-align: center;
    white-space: nowrap;
    margin-right: 16px;
    height: 40px;
    line-height: 28px;
    min-width: 54px;
    outline: 0;
    padding: 0 8px;
}

input[type="submit"]:focus,
button[type="submit"]:focus {
    outline: none;
    box-shadow: none;
}

input[type="submit"].hollow,
button[type="submit"].hollow {
    background: none;
    border: 2px solid #050529;
    color: #050529;
    transition: all 0.3s ease;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
}

input[type="submit"].hollow:hover,
button[type="submit"].hollow:hover {
    background: #050529;
    color: #fff;
}

.select-option {
    position: relative;
    cursor: pointer;
    height: 50px;
    overflow: hidden;
    margin-bottom: 24px;
}

/*.checkbox-option input {
  width: 0;
  height: 0;
  opacity: 0;
  overflow: hidden;
}
.radio-option {
  width: 25px;
  height: 25px;
  text-align: left;
}
.radio-option:nth-of-type(n+2) {
  margin-left: 24px;
}
.radio-option input {
  width: 0;
  height: 0;
  opacity: 0;
  overflow: hidden;
}
 .radio-option .inner {
  border: none;
  width: 19px;
  height: 19px;
  left: 2px;
  transform: scale(0);
  -webkit-transform: scale(0);
}
.radio-option + span {
  display: inline-block;
  line-height: 25px;
} */
/* .radio-option.checked .inner {
  transform: scale(1);
  -webkit-transform: scale(1);
}
.radio-option.checked .inner {
  background: #050529;
} */
@media all and (min-width: 991px) {

    input.col-md-6,
    button.col-md-6 {
        width: 49%;
        float: left;
    }

    input.col-md-6:first-of-type,
    button.col-md-6:first-of-type {
        margin-right: 1%;
    }

    input.col-md-6:last-of-type,
    button.col-md-6:last-of-type {
        margin-left: 1%;
    }
}

/* form.thirds input,
form.thirds button {
  width: 32%;
  float: left;
  margin-left: 1%;
  margin-bottom: 16px;
}
form.halves input,
form.halves button {
  width: 46%;
  float: left;
  margin-left: 2.5%;
  margin-bottom: 16px;
}
@media all and (max-width: 767px) {
  form.thirds input,
  form.halves input,
  form.thirds button,
  form.halves button {
    width: 100%;
    margin-bottom: 8px;
    float: left;
    margin-left: 0;
  }
} */
</style>
