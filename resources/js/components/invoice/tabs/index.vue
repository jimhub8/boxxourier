<template>
<v-content>
    <v-toolbar dark color="white">
        <VSpacer />
        <v-btn-toggle v-model="toggle_exclusive">
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn color="white" flat v-on="on">
                        <v-icon>edit</v-icon>
                    </v-btn>
                </template>
                <span>Edit</span>
            </v-tooltip>
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn color="white" flat v-on="on">
                        <v-icon>picture_as_pdf</v-icon>
                    </v-btn>
                </template>
                <span>PDF</span>
            </v-tooltip>
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn color="white" flat v-on="on" @click="printPage">
                        <v-icon>print</v-icon>
                    </v-btn>
                </template>
                <span>Print</span>
            </v-tooltip>
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-btn color="white" flat v-on="on">
                        <v-icon>email</v-icon>
                    </v-btn>
                </template>
                <span>Email</span>
            </v-tooltip>
        </v-btn-toggle>
    </v-toolbar>
    <!-- <v-btn color="primart" @click="printPage">Print Invoice</v-btn> -->
    <v-container fluid fill-height>
        <v-layout justify-center align-center wrap>
            <v-layout align-center>

                <VCard style="padding: 0 10px;max-height: 800px;overflow: scroll;">
                    <v-item-group v-model="window" class="shrink mr-4" mandatory tag="v-flex">
                        <v-item v-for="order in orders.data" :key="order.id">
                            <div>
                                <!-- <v-btn :input-value="active" icon @click="toggle">
                                    <v-icon>mdi-record</v-icon>
                                </v-btn> -->
                                <v-list two-line>
                                    <template>
                                        <v-subheader style="font-size: 12px">
                                            {{ order.client_name }}
                                        </v-subheader>

                                        <v-list-tile @click="openInvoice(order)">
                                            <!--
                                        <v-list-tile v-else :key="item.title" avatar @click="">
                                            <v-list-tile-avatar>
                                                <img :src="item.avatar">
                                        </v-list-tile-avatar> -->
{{ image }}
                                            <v-list-tile-content>
                                                <v-list-tile-title style="font-size: 11px">{{ order.created_at }}</v-list-tile-title>
                                                <v-list-tile-sub-title style="font-size: 10px">{{ order.order_no }}</v-list-tile-sub-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </template>
                                    <v-divider></v-divider>
                                </v-list>
                            </div>
                        </v-item>
                    </v-item-group>
                </VCard>
                <VDivider vertical />
                <v-flex>
                    <v-window v-model="window" class="elevation-1" vertical>
                        <v-window-item>
                            <v-card flat>
                                <v-hover>
                                    <v-card slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" class="mx-auto">
                                        <el-tabs type="card" @tab-click="handleClick">
                                            <el-tab-pane label="Packages">
                                                <table class="table table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>PACKAGE SLIP#</th>
                                                            <th>SHIPMENT ORDER#</th>
                                                            <th>DATE</th>
                                                            <th>STATUS</th>
                                                            <th>CARRIER</th>
                                                            <th>TRACKING#</th>
                                                            <th>DATE OF SHIPMENT#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>PKG-00002</td>
                                                            <td>SHP-78428558</td>
                                                            <td>24 Apr 2019</td>
                                                            <td>DELIVERED</td>
                                                            <td>SPEEDBALL COURIER SERVICES LTD</td>
                                                            <td>78428558</td>
                                                            <td>24 Apr 2019</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </el-tab-pane>
                                            <el-tab-pane label="Invoices">
                                                <table class="table table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>INVOICE#</th>
                                                            <th>DATE</th>
                                                            <th>STATUS</th>
                                                            <th>DUE DATE</th>
                                                            <th>AMOUNT</th>
                                                            <th>BALANCE DUE#</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>INV-000001</td>
                                                            <td>24 Apr 2019</td>
                                                            <td>DRAFT</td>
                                                            <td>24 Apr 2019 </td>
                                                            <td>KES4,320.00 </td>
                                                            <td>KES4,320.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </el-tab-pane>

                                            <el-tab-pane label="Comment&History">

                                            </el-tab-pane>
                                        </el-tabs>
                                    </v-card>
                                </v-hover>
                                <v-card-text id="print">
                                    <!-- <v-layout align-center mb-3>
                                        <v-avatar color="grey" class="mr-3"></v-avatar>
                                        <strong class="title">Title {{ n }}</strong>
                                        <v-spacer></v-spacer>
                                        <v-btn icon>
                                            <v-icon>mdi-account</v-icon>
                                        </v-btn>
                                    </v-layout> -->
                                    <myInvoice :image="image"></myInvoice>
                                </v-card-text>
                            </v-card>
                        </v-window-item>
                    </v-window>
                </v-flex>
            </v-layout>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
import { image } from "../image";
import myInvoice from '../Invoice'
export default {
    components: {
        myInvoice,
    },
    data() {
        return {
            length: 3,
            window: 0,
            text: 'center',
            icon: 'justify',
            toggle_none: null,
            toggle_one: 0,
            toggle_exclusive: 2,
            toggle_multiple: [0, 1, 2],
            image: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAACHCAYAAAHb1lR6AAAACXBIWXMAAC4jAAAuIwF4pT92AAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAOZlJREFUeNrEls8rRFEUxz9PGgu/kyFMdoYU5X9QKCtJFvZmZSELG5IFG0KhZKFGTSEbFiIlZa1I+bESyvgVxWZK1+ZeXXfue2+YmZzNve/cH+d7vuf7znuOEILEViW/saboKNm087UIOT57XgHBP1iuz3qJ6ShdmCVU+PLXeN3Ap8+ezVSAlQMPgKMcBiihrxlmW1u37BkEpr0YUyVzgDrgIjnSjzinPoDGUmDQLalvjbXJcUSOl7bDBzdh/eyU5b5GyTLAUToaU8B25DiuZR2VoI7V5oGDHp2VFWDbuO8MeJLz3UyJXzGTr83jQAXA1WvQrwQJIGD4aoDbTL2VH1qr+H4ru7YifoIPWHy36ZbSrVUUA5w8hvzYsvW600xozGaHkjU2rlr00qTSFpBtICvA3lXgyfZlgP50SpOuxvTsO5Qv8Vbu1UidbADLsQQZBkTezBJCOC0Agaq4W/BOj7v3Mv2tnAAmwmV3vc2rIzEJVhjsVANBrccpRmuBaxfdLQIRoEDKRHixnqSxQNGz+q2J/SLBG8snrMlFs+8GmAZtTx9QmQQsb3aR+vk5t1bgAK3a/Fj+LSgLG+dOjOchOe57JFgI3AN8AQAA//+8mDtLw1AUgL+EpgUL1segg5MKrQ7diwqiIq4qTo6C4uY/KAo6itCCVLBSipOoo5NOBWcFByc3KVYcdJAU9LokenvJqzbtFJKcwzn3PL9E8wDFdeBIfRjP5RnpqbW1I71GxZUVqY5DYsSnXjTVqRajtBRA5sIPDhs6auuPKAAmXHDG1rkFMk5E6rcpdOmlHJFrp7Qd3k/LtxWfA2UCRGW/mZqakTqt+7fFeqvyCTcd9CpNpvPBq6YGLMZyWinvAGOlHQz9y2vdnAKTYXbfi2JIAz5kQckht25cDYA7LY0EYQ2zDYBo4vU/vBX6nLINFwBS+Rw+jK6FSRS6zzhQjcw7yIQ+XHUP8HtSDIpOMZbs1LK84oBh4BgQj2+Dpov+Z7t337kdESE0LVXcA1gDSPZVYy76XS3aLwZNnzZ6skvUMEmXs3PjpW05PSmPuhMS29eBnEPKF63rrEttnjk6FTsoEDdMG12I6N/y6zuffwlDyndiXZG5BG489FcanIomaqTLWZL9z/YjU4KyBcWIkChiKuC8EsoKc5X5AQAA///smk9IFFEYwH+zq0u2CPbHQ0qHcKPoHxiZZVAHq0NSh+pSBF02yMAgCA+BRLdIqeggInUIQiI6lEJBYESFhmzWKSKEDqGRIYFFS9TudJhv8M1rxnk7rblFD+Yw733z3vd97/v/jWXbDmyBFUH3gFPApTAt/BOB4VwPkwrlbCJkAZflJoMB7RgrF03yL4xYEfZYHiRVieQ06290kLctE2dmIsmqEbGBjRHw7dH2MHaYsQBrpj+ZWeACCU/3t5p6fVNG6ZI9GoFZx+Xbe9pevy1Z7kabFINqB8B44+RXTTydSM0Fo3YXSataXD8fRQ3tkLBpoSTR7mgIupF4LE/XaLPf0mmFuY8NU1D9MhaUgs2yApBzRfertp7xu/nE0g+svX6OnO0R2iVAOdClzE2b5ura6C91A9+j2apDQeGCdWZAn7oJTAHfDc96a2BDdJs6MN8lIkKymi84NU7B3qKmt5NV1e9MJcRvtAMrCsChpEMHS2FCUl04O7SP6orPhTKqzXWcQBy4YIDDrr8pzvLNqY+uGY4iUVcE9irhXet5laoozFI91BPVftRVTbqcK1T15o34YpeLbJyG5mKZa7RnovHtKqH5eC5lOWVMU+KrIjKqooj0FxSGlBVy4ycHj4w8mqh7CCQ/ZitjqarJrQdWj1jtDfep6+18UB7LteCU1PeLam0QhjeKTborZYUs0AHcBg7K9luAZ9qRQ/LdN3EoeyR0aAK6FTNQ74P/CLAZuAMsE8HYISGQu14GHJP3jASqCTmvVmJBtzDVaFh1sOgbq+f88F5V6l5KkDmoqaitOQKX8bXAuBD2wse2jQM1ki08x9txTAFjAnsYp8XQhv+PDB4vreGxE2+Lw/0X5o0C53YzVRrSr2+1Xps1dEhUfmJdz0V+5H/R1jROnXJbiORmfUqDUwGwOcxK0n3M9GP80qGkz9yQ4Nqs7GkHmKITytMtF/jeU8/6P8LHTwAAAP//7FxZSFVBGP7mHr1aZgsZYZtW2KLS8lCIUFBQtGFQkBYR0UsPRVG9VpZI0Ya2F7SgPaRR0WJRES0PPVyheokegqKXK6RpWJQtek4PZ6Y7d+6cOWdu5+a95g8iZ85s/3//f/5l/v/EE/yrpudGs5fOwaGtmHL8ZMoTKt7gXwWAEIBxnMbM+x84Kx5isbjLWq7tUj+x1GbFPu7wnePU8eCjNX2GWGk+zEFAs/Biz6s21L92TCQYTyMMXqCUajQdWOEDbl8B3PeLWLsAVDlZ4unVVzBxSKsTgZ9RtewFniE2SeYOgGWKMdc9+LYQ7K+EcZbS5jCI6UQoADCZ7RLnOuQvJcGKZy6vFxZpkj7qHRHHLkd8EHv4NIfWXGkKjbYAwEPueTOAfK+bDwz/6LTmdr+4tzcP+LXCu4fC8wnJ5uucJi6uPSFr/qmxNyOVTQfZheZ6adjEDOBHj/Q4TNcIsXTT0FAoFU2H5w7iFxPs2/q0HAYxZSLFnxUtirV4r78kWcRRvApjeTmqX7OAQ+SISKhgVieehAtk46Zr7OtUKhilY6ViJT/I8yTcBlLdiMk5YRVXucErl766YeuEi2GXYnO5VIQI7OBcFOQPExNksVwTuSKPWpL0phi62STMvmrh2g5FieCID8gwusXxNzRNBaLR95/m48fjSDNkxiFSHmFPtuua2LdFkwO83iwfh3OqQVJow3cAJjrZQd+tACbFimBWArjqNYAtyW46FKkQm1ZXhUzjlw7y4qXqLB/PtF4n1nf6/0yMLBNLJBTgnvs0Jhk0XKKIxWCjBwS9Im/56Bwn3QH/Xni+CwCmRXj3pgApdCWfSGLlCYRYDJoN2PYtm7W96YWQS9IRa5MDgosANJ1bWAfY0cY+yVUqYskMvaOILRVjsLSi9F4d1FUKKc1VKmKx0Gs2It8qMAAgGK31/iCbtadhnSbyj/vSmUWoQ/2JEu52IKMLxfVVCI74AMHV2GZaRJdL5sfBgX5yIvGTWECkSpMYaT/Lpp/fH+g2A6GpNSdDA46dWpdx+MKtYFYnOdC8pDZvcPtgRBLxWZgnBLsu8S1dq0nUpBRWCus+gF1u2iwJF+0EsAd2Js5L2GlMIuxGpKaI7aMEdhkrDy+5vzbY3ztpB1AIO+uHjZ0NcIkhbrkO7V2DMO/qDtGGsnqsAKGBvkUUSVNia8nSkUYhEgC8DDvmnyP0b0Hkuoy1hSkiLIXJycaTrWlSl42/r2wEUA67mJmlmw9F5MtKBNDIdQhmd6Cwfq9UAQgRUd5frHSJCBgSs0RULrncc5kg+mHElnnXSET2hWD8VghjVkmILS2mCLiJdcPbmSg8XYuRAz/rynuP5rnw1cHyF7lzNPdOjF1vkMzbIcy5H+qcfILoHDLi6u5YpoHM2rNiXrub72gpOMeLq+WFuGHFuxmSNllRcLpv2jCj8iYmXKzWIdQXAE+4X8xyIEYuh/A7hU3HIFuToKycey7XVvIXGnN11ML9mX/e4TcAAAD//+xdeWwUZRT/zW672+1dtwgEDIdNOIqi8eIQERIiIJdGMSSgckSDogQ1NkEFsQFRQDRY00TAq6gUiMpVwIAH8I+gKBQoVREQqKVgaaGw9Njxj/cNMzv7zbU708POSyY9ZvbbnXm/fd/73vfe70U9LKuEgCZF+SaTFVP4cIbG3fEM7ss8h8T84jZfWvx/kLLimTEFHexwlosUHsBOUKq59Heeq5r2Gc2KF1zSXo90JCEyEr84ppHDCfB6wq422ymwoHK9JW+xDNF8SObmV1GAr1MlhPxidE+/4GqzHQOLJ32Yz1UCS7mdApbuH4ncpe+j1w0VVt7vTwA1DtxHBmclItp4AEQMJLayo9UA6xAoQWesIhLyHmQWVEPxCmFM2f4kio7dpWZDMLOI6AmZ3dcuuReM89ZAqhDNBmH2AIgoM57Xa8lyu8dNMLmSC4PSy/axqasMlMJm1lT4IW/8S6KksZgN4vkyXSqV7gtBFAWroHJCXkQkrYeZBUyLRAA4z+EUHCpP0wJWf45luxUyoe4xdgwAsQOrFXgPA6EVhUq1eIYK8IiCFr9Uc4NqA4yLg+ohF1i3ihATmqHmUQtYRtWpvW1UaArkSnjjmFXWOWS+WYQuqdUtDSqp8N1IHmql/vWMlnDefTqvuWCjQgUroAKA8MUgvJ6wGd+qn4OgEmG+6q8B7VB42jGi8LrDBlA9ypnyDP0UX4dKeJd9pOZQ4skstkiw6sSaBdUJzrgbm9litjlgvWbwmpMG57VWXL8qlLBe8f/joJ3Ol/Q06UuuRdL8YjMbmFshJ8fzZHOcoFoHPgvaeI3XDHOBRfIb59sokSiPM2H2y0B7gOox1OxDIxTLf83pQhQ9OF0bRLeCFeiecd7ofmpAua1OrMBEAFMRnTxiJHNhD7NBmxKzN3ybzrk00LbMSuao6lmELqAApR/AQjMAKDgwHKuODkQgod6M4o9Bg1bCBlD1YV+aWKSB+ZIpLrDMi+TwqJlTMpn1+FDxQIcikiwuFbStw7VYguhBVuASGpq88AiikeILwW8yEo90BfGQJ4GfrGRFklX+1moQFX6I6WGtCyzrzvtGjg9yAHxyPpqjBRGzvnsMu8/kmAHVBPAzb+OR8aBKUiem1WdA/HiuxYpDtHreaCos0RfC4DWv4uK1ZL1AaBJoaygIyhO1UwpB1W52guopZr3brfNuh5SCaGrUiskzApUv9SK6FS5DdShFD1TDGKgEB0B1yAFQCe0JVE5arFs0rNRl5mdpTn8vlExDcmK9XvDnbVCNfzyK76zx/1q2GLETVLPdcINzIiqc9R+gE7PyeXVpzQ/YACrlZxJBPAfS7zUO+FRHXWA1jyMPEAVLFBOkKAKJ3iYtzTaCNsHtVrzUgbYEMkmRK60cWBKoeMUSA9n5zfLFAvJHfILy6ht54/wM51jsXoGFfDBXWg5YqQpQpUBu1nCQc+2D7NotAIC6DIzqUaoG1UJQKo4Tcj+I5NiVFgaWmQbCUnnXy4jMXujPAMYjxBoNQKxvSihZM3IVaq4FJFANBVWLOyHZzOdzpYVXhWa5h8eBIvJLdM4D1IzoOeUJr7dx5PM7nkAwUCc2hj1pbCXphAiu+luPxRIAZKlWVeqjHzufjrAXIUHExC1Pw5d5nah7EYj9HIhmnSuvvZombD/VF41hj+AQqMY6CCqBY92/bYV6F6DRd7qlLBZARQNaipHyoIb6gpVIXLAOORlVhLaCFThyvgsa8ibP9QbqtuJK2l6kXsqoP9+5CkI4HYDfl1T3+KgNc5AduMzr6NMWZW97tViOlNj7UmqRuPgz3Jx5LhGU8/0O5LbAAnPUBwDYBkAY270U6UlXsPrwIPi9jSKop8JBNlVOUAFZBOV8TVdY3Go2lgjKnf8bRGI/GURFqWxJ3Jd9ngc4X5AvQeQzvC/O7SA+lh6g9k8rQZvskgW/Bur3wCMr/AfUf72AuQELQbxXUiPbcujvdRaA9hf3s2fyCBtHyl8/CLnY1wOZwGcbaGO+UPX8BFBX9OkgCs4ZkDvkLWHP/CvFgikMij3mgoLfuaDdlUmILNJYW1Y8U4zVYhnOrfN2TUIO9TRsUNx8Z/aAlXEpAMCmEzR7KuiEmxg4JiKaVF8C5nwGErVIJdFTGLDUEdcjoO5ypzR8yB3s55jrK1VKRvxGcc0qdmhtT3Vk57aDGJ6a2P2WsmMRO/85A6iRXysgkiqwO+RNfYHd802I7C4qPcwvND7ncnaMYYsp5fld7OdPis8wBMAe9vdhRLNEORBuCHvgy6pEz5VvIffT11FcficvKvos7OvVuBcU2Aw4YMX7sIeVx2JnEsjmaFzfFYDazN/NxpD6kPPysOphXJTyLwOwWvI5z2MSgA6ca6XdhN3gB6v/MGkvpmq8fjDn/mO3WKLogb9TBbLfKEIwcAmCICLVr1sXcRKRTPJCnIYxpBrPyurPTB76fSAK2t8Nrjtt4v1jzXv/HtSvykhqmVXUo9sZorBultXNps1pzEAoGwceV81E1oAlhr3wdzwLhIG/Knqg5wfvonfwNLKTa80OoZ6S9uhcKzVf3amz6DDqlSzF237hnNsEc31r9EA1yIJizsYIrIeZUkPQ76Y7GnK7ULPhophtigo7FXGtCv0ZVUiYtx45LGzQK3gGojWjo0ZgiH2DfoTc0u8I+BW70u/72DSjNb2AhSjKmbXhxd5440q+UEDhjGvJVdVn5sX21NmmQURT4/lMglsacwGIMlTLGiq7mn8Nc9XZkh5407SW1IG6bi3j3P/HbMp0WepccUb+Y+9Ko6uqrvB370veg0DIXECoRRBj0CIiWAfqwqU44aw4UAVqsaKrVo1j63IxRBGqFMQZtaKgS6ho1arYorUtUkYRWOKEKCKZjDGQBHhJ3rv9sc/hnXdzh3PfuyckvvutlZWVmzuce86+++yzz97fThIsRWx+ADlE18M9ZzElPLnlFDz60anBaB5kcDY//1aFzhgBylV8zWKOn4lga+VHiY4QrJ02xp8B4A9slcID7gIEgiWNGguj07DxHwUIBMsTRIfeqZBzyskbiggWIJkqWHxp/A7I6cdrX/3WdE5/rzcNaQY+q+8bjGQGCxZAviIR5pSoYz0LVlEtHvnwtGAkM1yw7HB3KhpL1+O4f/kEDCn5NhjJTobOwIKiIcG5IJ92Htfx6e4SPLhhDEpyGr0873i4MxaminzIkdymitNB9aQ6A0Igqsx4ZxSsk5AIhvPkzwr3rsLP5y3wUvCHYw3k9hq9wgDlEPJQntdB0ap+YCrIydzZolErLFb8B0WwSpC8A8+F6gJPajY7ijOfuhdH9d7hNdI0qui9+L6kuB/qlPp/LSio0GvotVdn8ijI1yl6HsDErmpjfce+7CVIztTxtNWjh/djxTdlXoWqL5y5VdOBbNHii5hwPA11SSIiVrLnTZMQ2Il+PvhgTYVmVryNXleEWbrnmaxS0buMc5ga09E2fmI6iPBukQ9aMC2NNRnJmTdVoPjsmaBgr+Eev/7v4FwiY5jXGcFjb/xG4aAtlRCsgZ3AHloMSnTpEGG301jmokl9QD6osxzudTVrvBmzkKjK64R9kA01NjRomidv+9OK+m+uxDl1SK4WfDAxVBD4LSofZKexRkt06EbTsUWs0aK6PQzyNQi7QTaNPtSKuHwNnWUK++9mJ4dIJ9JWIr5mv087GILlhnI2HZqZkXsBeA6UGXI9EvV5nXC7cP0a1wbrMVS8exUGJRJg3XCxor5bJ3leYycTrHsE86RDBaunyzWrXTpxBShjpEFmUoN8CC0QD2FnUz4e+uhU2Vo6KjtvBLomKjviIXoKhu5Lkvd+0W+NGS6uxMDH5qO4u9RKvUjStksFrZIfTcbCamBvc7lmvsR9R0ucc4zn1u7Jx+iBm2TPrlPUZwXIwIIAfghWfx++xH9JTKdmrqwLXe+a14C11YfJvNfZ7PdWUDGlfqBqXX6Ae9QvYKvY812M94yE+cvzo66eTFUscw7cPJcVFnRDw9wV4zGgV51M5a+3QPV6xpk+mDWgTehUwd0tooHH09ODaEMHjTXV5XwZ6hsnzfAh2jvlTmAGv/MXUFiN8n9fJiNUfKq28oinywr4NpIJRkR8FdhY9hprOmij2K5I4oI0njXSZlD+BxcPcFZ2FGOevQ9lxbtkVoM3gthY/MZs4T2s8EMgWPYaKwraedeYkJnh5mcyF8ncDuI512yEyrVUr2FoaGrphg9rDpURKp7po8IpegeIfsgOdo3rEwhWMqaxzrraw/0K2ZfdnV07yGEVyQsrOSJSUIt+T8yVDeYrQ/sa1X6AL0a8cKFy431HIFjWWMyExI104gp23l2w5gMQwd3mztW64jqWbxqNQ+TqPxtsxabCKToaqRe0DINokALBcnAR2KFU0v6awQSgCBI76+HiKpz9crnMFjzPSSxS0EecTeV9j9cZppXjMYFgecPjIB+UUyz2A6yj72FTq1whgJYI+hXUyJy5FWrCdnOYjdQ7hWvNxvtHoCjNjFwVpgK76Ww4WwhMYX/vBiUbiDgJFqVPDqBbFLV7c9EzzzGimGf4nKGgf5pBe561Pt3vavazDlRbmm/SN4FyLgPBckCbjUbKt7DJbPcddUPDjLcm4Yj8WrQ5+67uhfs2VCrgNmVBitfHXVwvIyVXlRk7FZrxguRS/ARHiS+uxtQPLnITKs7WN0fBe6yCvTM0HWwBMTubQ46CqdAFKwFMEP4+xMb+sO3MrFAbLnhuKoaU7HTztB+ryCi+VdAsqcLKQZoNgS06MN69QaQsnIz2HJV/dnJdGAD07Cj+uaPMTaj4VLNZwTs8CLkoDi+CpWWKUKkSLF66awXaU0nngmitbV0XkZxGDF7wAH6W973TMwYpnEKWs99+VkbNOHI5lXmFYyyO7YHDFodhaKjbXYzGlu4wnLdvtoHSmVTgTNindKVivL+JDIQqwbKSil3M5WDrmIr0qsegZ2Yh35kvnm+QH55G++ySEnlkxss+9sW5gWCpw5XMiHdOg9LiaG51TVd8CsDDabbHqlRKmLXRr03jILqhA8Dj34elOc3wVPzfp9mejy2ORdlPDQJ0CcEyTytL07jXeaDaLX5plM/Z9Me1S7dAJLqOYF1iceyxFO7D3RarfGzbYCT8bH5nBscDwVKHDTbHrfcX9bid7yqP2T45Cts6NNAzXUewhnsybtuy0bfHbqtzG9iUtU9RO59UcM/AeFcEnib1jGznx5rzcOngdkqOp3L1V9jWKYFgdQ3B6oZEVMBkOEcIHBiAmKHh/IGbzIQfb0E++zoVXBVMXF1HsHgs8X5hKsuVEa7TB21CVfOBmEG+X3elwj54QdF9A+NdkcYCiOWXownO0RQkXD32YG/bASfpjQAmKXz/owLd0jkEy2tA3Qemv2Nw3pA10NwTRxftAhJe+ucUvr/KwlCBjSWJL0Gx6xtdzitkv51yEG2Fq21/rjHuiA0AVWpXSVqmOuIgECyP2mcYElnBTsbwCakMbFZh9ex7Vl5sgLZXVFEsfoUAnUawJiARR3UHaNc+n/2Iq77x4kXhUKtTCEwS6Vp2dhQnLfjT9LLiXaKdpgIqNGGhqR9yM8yuTushJyKRWfIGW/39APJZGSDWmCFg1QrCBTUYs+xWRHIagfiB/IpVSFSYTyocoIViWv2+HvvihqZymlJ17wGg/EqOm2Adk3awsaIDzICUpFeM3jwZyaHIN7EvtSKc04RhjzyC6uZe0GYtwtamAs7NfiKoWoKBREUHAEC0uRdev/BRtMVD+JFgBTIU6arFVaDcQI0JyUoAMOIhfFY5EA2tEbTGQygtrMaYl+5ExdqxCJfU3B0Otb0XLqxZH+7euBPQGukaHZGiSpQ+NRvZegwBujaUVLHP6t6I0KwXUFpYdQsSnA6Grhn4pPanbWNL15/85mcjlxxaWD1xxw037Y/uKUCk3y5od7xZVNb768VxQzsbFF7Tk02v/2C37seOZ5k+jgmgbOVrQRz1f2TadDvIFyZiGYi0ZBraVwFbKUzTVniR2ZCvsLaIbZjBNNTDaE8DtQ6U8WPFD6aBtq2qXLr1NADXgUhPZoFi889hZocurEL3gbgm/ob2LInloMCASwCcYrFyXQ2Kl2th7QojkVG1HhS2DVCw5VAkCOd0AK9+uvT6mQdkwG9JzdLjuPyVW3BkUSUMQ7sCxKB3HoDcuKGhtOTb+Lb6Pigt2XmZBuMNbfqyxcacc6Hd+jaG9P3yjJihc9a8OlDtGQiC9ROmIa8QtK0OqrpVzgb9VyBm4AGs482CVQeq7PqNhWCdzPxmEx3cB+NZuz5GgtZIB2UyjwKRA38A4Fm+JkGCYXkRG7gT2TN6s8FxEyr+7JmgYMe3QeE+x4HI5M4S3Dx7BfvVnGY3CZQyNw+U3V0B2jJ7n523DsTBsZq9TwU73oTk4MjJ7DnXsHfXYKIH9V2w9MhebK7rz+s0/4K93EhYsAUa0GJlfbejaPpClPXdjpihi+lRN7ABFOfFjQDeBe0dmr/8GBLbKAvZoM2yaOJ1TLBiDqvfKUiOpJhnoWUMEJ99khuOHf8L0yhVJq0wl/1E2eCcIukPm49E1hDf3qpiA38zgIcAHGlzvVjxg/fRBvbzOoBNAMZaXLMFCS77/yCZxpu//xokChIoXnrmN+Pz75NqNE+CTU07AIgbGkpyGu1I1awOnq7Yfrxc+OrFRYlZi9UzAbZqc54wEFbvcCGAX0q0ZRsos0lMRYuY7llqWo2K6M3cNnal5TYL72yHS5lm7Gc6/hqA99T7NAwd4fzv0GfG8zg6uTjlWuHL89Mbze93u8+fxlIQj/tC4Tnl5hUsU/0Tbd5rD4gR0YA1J7xs6v4gtOe8WGgxtYv9USj8bwQTwlFMC9slzDpVa/8rKMDSzNv/DqikjeW4ZvkxupG8OixYew6uW/5rlBVVoTXZXcCnpx7wNxP4eDbv71WwqOFGa4MwhU0zndMCYusbAWtO+Wkgqm6rj0nGn5Jtsq/c0J/1b72FbcanrnpY748aLn6/oWzKHMBsXAgfTAksiPbSEyxDQyS/Fn0eXIj8bk0oLax24gn1WwB2KBIqjttAOwN2L9QCojlyKlQwnNk1qcwMXp15bjz2gwF8kWJfbGbToRiF2coEss7XqTCc1YJ3Kw+HXvEyCrs3qvKVOZGHpDutunmf57DVpx1ikPNgp8qJut/nD2Ub0gvtbrBYqGi+Dno4tx6/e+8qTF5+DY4oqHZjM3b6Z9zl716g7GmZa70Kngyx6RKH/7VAruBCQ5oCscRH4frExw/RkQ1PeioM92wAwlEgAhw3Zz72xUPIDUt9VLrD1HitqePMyaw5zDUgroiikoJzP5ILVeomYZxhsqlaPHb0FBeNJqsZndAHQDWItXCbzTljvXiDfBTSJ2BdClj+QbpmYNzS26BNewXaXa8iamiyZd2cnnE0yJt8A/v7LLbsPU4QnKeZHQMQZ9V+SY31X6YpyoWFQ0y472xBQGfDe3X7V5km9VObWKEG5PD9wuFZf0fHs9lMAREJT0hbYx3SswGlBdU0uXrbJLZTa1+BYrZWA3iUHbsEtO3BfVWTkbw9ojmo4oggxKMstNp4JGLF7kRy5VeZgYmb/FDm+39po8GsPgAvts6LbMm/1fS8HkgwGtoVNjdr4QJY52aa25hnMW7iQulxl+cm7xUGCOAX9KALAqjA/9k78/ioqrOPf+9MMhNIIAsBAoho2UFrQXGrVkArorR2EVo3rG1VrFutrcWtal+sta0KatVKsW61LrWIUnGDau1bUUrVWlkEBdmSgIEECCHb3PeP55l3ToZZ7szcSWbk/j6f+eTO5N5zzz33nN95znOeJSFjuWXt0AkoAjaq7qcG2WCtRowRH8+HBwiUbeXuN87gR69NY3DZNq9netjvseqpiz+zM+E0JP1zOZL95ASVo/+o8mV0GpyziGxx2YgFzQKV58d4XcWDB090zyYeIbFP8xJE53yXElS0U/1AZLv4l7qAt3EnvK0HDx48woqJFYh2Lp7P/Aj2tVFOhEle9/DgwSOsbGN0FDFNpmMipFOR7aOaJOVMIXV7qLRg2xb4bUoKm5OlePHgYb/GZ0XpHgt9EKOIxiTn1dHRRNzEOLKTVS5CVlgEe9SxfssQxj0xk+LCFor8rV7P9LDf47OsdI+FrQ7ICjqmeYpm729kW7IKFtfTsquCSc9ejs+yPbLy4GE/WxKmivWIJ62FBDsI4y0k+EOWRFuLYN8aFq44lm5330tzewEVwT3e2/DgIQEKvCZgBhLAYyWRuAfnE9tt3h2pqvsuQu2FjL7jXtbWVzKyYgttIX+2gpVOQHZJ1yEe1flgKnwZsqP7V2LneRyPmEWXkBvhzi1EJzodWKW/XYmkp/YQH3OQmCMeYTlENR3zXTYh8TN2ZYWsQj6CvapZt2Ekox+9kV5FjXyudFs2Axf+gIh/SpFK1LkezO5eIrm7KuOccwHxg+o4QYO++70k9oV2Cj+iCzXJ807gXSSwpVukGtQ2GYpEH812PrFVSOSw1YiKxS3faT8SEWuxJ2F1xOnATCSE2AtEcqYORIxFiepgP8reFGwTKK/l/tencvGr5zKiz0Zs28LOniOgOfDzBa8iHqbJkIqi7yl9t0u74Hn+1kn3GYeE4jspw3LeVDXIa96SsGuwANFRvevg3CsRb9A3s1ERn2VDW4D/bO9HILA3UcB7N7AIcSfPJ6wGhrlU1jLEnKVuP1kpLCMS7/1MUndHuwy4Jx8eNFXCOgiJE7Vd1+ybkFhMW1R62Zbi7Oc2DkWCzDZoXdYhcQWciv3/VKnsuWxV0GdlXYX0AZK4IZ+wk9Syd8RbYq1X0tuft1r/pJ9bdXWRCA+T3Yx6XU5Y1xI7EX0qaFcy2apEt1k/m3SW/Xua5b7igkgclsou0SWVe0tCywbbR1NbYbZIy4cE1CvPo/4XUL2IG+LmjXSMRrW/4xokKFG8BH2nqiSeVyhIcUB8zYV7+hFFdxWJlYabkdTk9yjBxcMZupRzE79FwsO6ZtZgAYR8NLUFskFYlUnaKBcRS4/oFNENeD2R9A0eIngX2SWO1qVdkI9klSphXYjEWk4XM+mYMTOIBBQfAByoBNZbPwdoh76cjtkG1ur6/C7VT6wBhrj4creqlLJd63c88aPtOx9dtg8ruBuAbU09CPrdDEPOKDrmH8kHjGPf/DLpEtZCj6wS4jUddz811B6/z9eHSYWwrsjgPnuJbK+H0aw6h/Xsm4Q8nVk2XfwDZ+lG0pOsbB+BvtU8+9aX+fqzl3FweQ19uze4tTs4BYn5lU/4GhKz1S0863FSUvzdIKwn8/lBnBLWUWRm9/IkYnfhFmpcHDwLstW4hYEmapp6MmXOHP5b159D+nxCa8jvFln9GPh1nvW3S5H0jRkLrcbxeo+PksKMnbwrnx/EKWFlap802+V1ed8My9ioy9CswgrsZcuWoWxqLGVAyY7oVFWZ4CFip+bMZdwGXJ2Fcj33MgddMc5x3sHJyx6MRPRMF/NxZgflBIuBwzIs43oHZOUnkoI0g4VwMYcPXMXYPp+wfW93t97Zv2KQVTuie2vL0X72ZAyyCqdJa06jPC8TwX4KJ4T1TRc6qxtYAEzMsIzeJFfQ/gLZvUw73ZptWwS67aYFi9Fzb+PNLUPoX9LgRhtsIJJX6A4iMb4K9NkK9XsRkiM7F/B3Y8K7x6hzida5yPjN6RLXIyyPsGLiGERXki7WukRYjyAhjNPFTTogEiU+vRzJOXQ98W1XHJCVj2Dpp3xa35v+985hR3Mx/YrrU0mMFk/ia0N2TkNIvqSrEpzfjFg8D6JrfQfXEtnQOIHkEV+vVok+FYS8YewRVhjnkJkpwy9cqOMtwLkZkJSF5I6PhxKdsSchydXSHgB2yEewdzX/u/pIet83m7JgIz0DTZkq2Q9Usgo72PoRy3CnEtmCLupbOw3yGYFzg+CPEVcTpyQ1xBvGHmGFcYkx6EchitNGh2W3As9kWL8LSc140ylJhfEcsmtyDqnlut0HVshPsKqaR984neMev5aRlZsp8GU8+X8J+ESPVxI/ekEiBDq5T/XQCaCHSndViAdDKkjF3GaaN4w9woqFlYjxZ4kSQkCXi2vinD8nBUkgFr4B/C7qt0+R2E436HLVivrc7LDsS3RQfQXxj/xjpo1Y2GcLM+dfxPQXLmB01bpMl4AgqYlf1+NF5Id/4Ajjne/SvlKbhfuYOqyJuLsL7SGHkUm0hlbgdv2EcQpimX4M6fvinYI4Me9SHcwGF593OJJVx4c4SJdl3IAFrfi67earD93EC+sOYVTf9W7Et7qdiClJVsPeuIhjiRgAb0L0bdlCtNL9CpU+z/GGtCdhpYIXteNaSKSEdNBTl0IPuURWA4BZyK7fKn3mv7pBVoWBvexp93PE72/jbxuHMay81o2sN/MNgvpenpDV2QZZLc8yWSWqg032g9p5+AwRVjq4FHHd2Y3sKr2kS7V061aJKPubtQNvAq7TATVcyXSKGxW3iht45P0vsbxmEANK6t1YBr5DxMH8OODBPOhDNwKP6fGfgSM64Z6JlIPv6f+/7g1vj7CygXsQW5wzdCkZNlhympGhH+IM3aQEtQ0JrRHQsnsoSZ0EfBjNOYjtUhprEgtaunHWiLcYVlHD7tZgpu9hK/AF/V5Fev6VnY1HkY0OED1SrmTLtoC/aH9oAe4nc4NjDzmAXIo4+mKC/12nov5QZKu8Z5KyxpLclupcJC19ypEObNsiWLKD+obeHPLIzbS0F9Cr2+50o4hWIqF0AjrAinSQ5Tr+RiTLkOvxw5LOFyms3IGL9OMEd5DYxs2DR1hJcRIdU3DFwy9VukqEKsScYSoRk4HUyKr0Uz7aPIwjHr+WksJmKooa0yWrkcgmAEgwwwF58j7eBw7R40lIooLORLqW7iGVstcQSUJhjoUduOOc7WE/J6x/JCGsD4wBlAg3IFEp/aRhIGrbPoIV1fz7w8P54lNX06+4noA/bUPyU4gEUXsT2azIB2zRZXiYcFflcF3XIqY4z3hD3SOszkQifc5ROAsGV0MmKZ1sH8HKaha/cwInzb+CoeW1mUQOvdhYQuVLXO0SbcNi/d6bxK5O2USyyaazl6geOgn5EppjKfvqde5U8klGVuOVqD42JIOUYIUkCN/TS0/lpGeuYkRFdSZkNdsYTNflCVn1Q+ziipHd12AXklUi3KJ9wiMrT8LqUtSrlDUB0Tsd5PC6xYgldLwMwsnJyvZR2K+a2S+dyZWLz2F01TraQmnz/IuIzgfEOfmJPGj7Q4H/6PFmJHx1VyPWbDEE+Mgb0p6ElStoAn7ikKwO1U49EfhDumQlLjfVXDf/Qq5cclamZPWhQVZH5glZnWaQ1fIcIatYhHWER1aehJWLg8cJnkQcYm1EyZ5WggLLsiko3cZV8y/mjrcnM6rvJ5mQ1R4iAQEPRCKedjaCpBZq5jLEvg0ka/ZpOdovZiuZevAkrLxCTyUp03t/FrIFnzphAYT8FPra8Re20J4eWfXWOnVDLPlLuoisACpwniD2LoOsHshBsjIlrNe9YewRVr7hYCIW8h04BzF3aOiCOh2GWK+D+FX2wHlonmzhKzrYb0ccy8PwA6MRGySbSKC9n+Dc4LKrsNsbxt6SMJ/wBZJbtYelr550TtaQqcBTepzVNGJp4kckd6qeivgG5iLsGBOTB0/CynkcS2rhjHeqNOZwWFjphIq5ySCreTlIVk5wfA6TVTRhefAIKy8wifQchD/GQfLRkG1BYSuH9dlAyPnweBqJXgASG/77ediug1Uq9ODBIyyXcAaJnaWTYYrO0i8kPGtXOWePeJvjB6xl655k/tb8W+sVrl8+pk8vVkLPdXiJJzzCyhtcpJKMiXCI5FR34CYrccWUuJpbA/jKtzF91FJ2NCfMK7gZGKPHh5F/vmvbtf32eEPCg0dY7uFnSGwjE2YMpgMRxXqqDrlhievlaBZkTzGnHLiCwaVbadw35lUx4jLUX7+XEzG0zBf8F+iVZ3X2dFgeYeU8jmPfJBMPsK9yeBcSRaAEeCvFe3xZB8Nfwz+0NpUw4IA1nDliGZt2l5vnDka21Av1r4W4EOUT3kC8AvINHmF5hJXz+HbU9y0kthFqBI5WIlmS4r1O1UGx0AZo9zF50H8pDeyhTWK2j0dClwCsR2ys8g0PIbHzPXjY7wmrQCUfGzElcGNAT4j6PiaFa09U4ko1C/VpgB3a1WvJsUPfKTpzxDI27Ky4AIm2CRJ//uA8fO/XAefncb/1lO4eYbmGu5EUYN/U7z2UtDLJQDyejnn5phKxIk9VSrOAuY7XHiEfvp51EzZvO+DIRetHf79v910P6L/uQoLw5RvOxp2M3B48fCYI6zIlhWizg6+qxPXdNMo8zzj+I5kbNV7oSOKyLYKV1dUvvDfeOuC+O88K+NrnFhc2h6+/Ik/ecWGUlPp4HtQ5EPU3Gp51e2rwxzn2CMvAZF0aRjsfzzOWio0xPk1InHMzJMyp+ncFRrJMO+QnUNxAoKiRQHAPgW67sfe1TC9LUMeEqd8DPervWv3R5/uf/8r0koPLa0t9lv2KSnpz8+gd/woJaNcPeC1P6jwTeBa4Ns7/Tfu5TR4fJcWbyEbSMjo//r6rsGw7/oZLy/NVbt2nHJiBONz2R4KtDU4wg0ajVSUFCc0S8hMor2HdtgM55U/X8GHdAMBmwkEf8PQ376RXRS1tdVVioW7Z30FiYoXxMRK14D0kQ0p5nHve4sO+vqC0jhte/g63vj2ZIWVbva7vwUMnYdVTF3cZYTnBIMRCfBoS4C4a04Cn/Rb4K2q56tlLuOPtyYyuWv//caoKfe183NAbbB8PT7mfM8YtgZ0l3VuaejRipainDfmmBkrr/lxd15+zFl7Emvo+FBe0YFnejroHDx5hxccXgJPtkP+xYI/tWyhoZeIfZvGPLUMYWhY7NbzfCtHYGuSTnZWMH7iKYwauZlTZVs75/Ovs2F3Ove9MpKW9gO8e+gaDDloTYEdZt+bm7gHL197Ntn3FweCeIno0fDDzuRktty0/mRHlNZ7hj4f9GX6c6w1DuLSLG4uw8sEO613gV8HKLVseXDYZ65pFbG4sY3Dp1n7tts9G9GEdPu22zy4qaLWHV1Tb1Y2l9vzV4+xZS6csLr3zAUY8OGvi46uOtJ9ZO8Y+6vHrpltXvtpy3gsXNAR71m0LlG/fECyvXfnrpV95x7phQcsTaw4/c2RFtW1LuZO1Pg/GuGc8A9Wfxapfgk/YF/Fp47ewFX0wzjVjk7TfyzGucaJ4D5ulpBNv6i7jXmX628oU2iHszXCc8dttSQZU+LywfrExxba3kQxMmeAU1Rcluoe5Q7s1hbr9UK+ZaPw2J0l9qvW8x/S7ncZnJ9Cmahknn3a9bnFUXWalcM9qIjHZ8o6wCBQ20769Dw+tOJpeFbVY2NhY1UAfbUwQ41Ar1se2LQs4sV9xA+VFe6x220dbyE9ZcE/riANW82b1YPrNvp8T5/6cvr9+kPvfP56RlZso8re2hSIJUsNC1neNsmuMjhcLPwcuMGaeGfHqqJ/w7medcU24As3GeebUk0z4O9m4LowdDpo93K7Feo8THb6u6M4WJqyRxvPtQgIrxmuHGTH659Vaj6Nj3NNCDIkhkhy1OKrMf+vvaxLc9600u+hCrdsivf/YGGXfaDxH2JO+DxEleAMSFTZe3WYbzxrG5Xrf4+LUq8boR0SV5ycSB39lgvv2NI7D/ebHCc4Ph3uK3my7HvieUZ+ZCcroR5yEtjlPWDZAST2LN45gWe0gKoo6BO3chijjX9OZZxcpbnnbWPisEKXBJjY3lVJetIdCXzuh5JmcCx3e6/fIbmSbSg7zXGiW8jSuGZhO02sbA7yaRDIbo9dUGQMlmlCnIrZwPRAfxkkp1OX7OhjeRCK4porDgfuAoVreQBfew0la1mnaNhZiOhIdo60BcSu7H9k532n8b5JOgqWIE/roFNpkmrbHG0g2qaIk7zJ66TZEyXaknpMoyUhPgy8qYvx/jpYxRsv7YYxzHtS+G0KytN+TaoPnLGHZto9AQSvBqlpWbxjFxUvOprxoD77YyvPw9neJMvNE/UxIc3C7jToiMdLP06XfeJVacj3j804diE8hqcka2NeW53ZDgrGAqxKU96SSH4gv6FHAOJWc+se5xkLyII7VQXqQDo5wm7Y77MvmsnJGhu0yEXjFKOvsBOeWxpCMTfwBcfECcZk6Ctl4OlongHhtslf7z1RkB71JSSGV8T3TOD49jXYYre/icmC1StSJgg/Uq7QVbreTjfefNHt7ToVItkM+goFmKN/OluoDuPTFi5m/6igqS3bQt/tOCn3tiQghjEuAc7URQ8Ajcdi+s7Hd0Lc8QcRhuhExRF2Yqyty/fstJZoHVFoMp6lfj+zwrjCkg+EO2+JAJEmubSzrjiK2bVW7od8LL58v08/XdHmTbJdoL5FEsEUZtkuY/P4J/M6Fdq5VIj4iqk3WIRtPOxMs2/+s585Tae18JdTVwOcdLv3T5YOA9t3D9L3XG8vRI4kd8mmHMRZeMp61SSXw53KWsGzbR7CwGSq289Gmg/npS+fzzMpj6FWyg6rinQyvlL6bJFSxuTQ7T0kq12AZA6/E0LXkE+YiscOqVe9hKlRvSEGvFs7i/S6p+YSaOAMJi/MBYmTq9B1YDuuYbEkelgbcytrTpH/TTvqrOqIf6PJwSYr9Ml28gyQ3MfGc/rZBl+Bro/7faixLU7K893UNSVn4rRCB8lrq2guYtnAG1sznOebhm1i1vR/De2+kstvuVPIA+mJIBLkGn/Gy8jG6A8bMaWlnRAfuDSmWEZ7Vgy4statUqu5M7ECCNgJ8zqUymw21RqbldEWbmLg0SkcX7/37EAV7bhKWHfIT6LabYO8a7nn/BApu/guj5t3Kirr+DO+9kYqixnSSPkTPEslmzlAK59pxrgs3esgQbZ22tU3mdirtacyQpujvhj/ZIL33BxkMTrf63zw6PxbZb42lshsGi3tdfDcg7lgruoiwCpOMsZZ0OagTloQW2BaBimqaG8s49YmZLFo7lsGVGxlWUU3ItjLJqBzroZORUK1xnEzSKYmazU180ZgdFqRQRzdsUMPb2PU4zxo0zjh+nq7F2ChdRqZoR3SBZZ34DHci+rZv6RL5eDJL3hG2AVvnYh1bclRKH2ksgz/tMsKyAF+giVc+GsPy2kGUFzXi97fR1F7AfctPZv3ucoaW1TK8zycistiuOd37EkhC0ViBKG6nIgrkmjgDeAQRE4TX6JgO/UYknRfANcRWut6N7Bx9Wa91Q8IKKuGW6gDtG4MAByJb3R8iitewNPADPT4LI6KqgSeRbfoTEJODbGETMABRkh/jcvfrbHwbeBhxxn4D2Ug5ndQCRgZ1edkL+Bcwnc82HkE2xUA2GZq7jrAsG3+wiUXrDuG3703o4Cxs+do5uOen6S75ksHc8XGiw5qGWCUvIrIjsQbZ9epDR4vnGQYhDdHzwnhAyednxm8DkF0/gEcNois22jzZ4ArEIOI7gCv1eD2ySzUzirTPBIbp9+OVdE3l+K+QgINmfYcYHeh2g6yCGeiZimIsP3+K2N6AGNrO02ewop6hDVGiL416l8EkZOWkvgVGmd1d6nuL9P7jEZs708J7g/apOmMZvxK4VSca851W63PHa5OH9dpACn094OC8Qodt7DPea7K2M8vsZqxITAn0N4j5ixVj0lmuK4fVXbAk7BS8jVjf9qNjZIZEeNFooC+pZFGpA/bWOMu8tToDjtW2iza2s3TGuFYHpyn9/I/+/z0i2/rx8EsluOVElLt3IIax5VrOqBjXPY9YTr9s1Od7yNa2XztPt6j61mvb3R5V1iyt53NpvI/faFt+QCRt2GO6BA9bTveLQzwFRh0XI8aF7UmW3a1IuOzJeu9E0t10xIj0Zpf74GtK/mFM0H5SpURgaX8wl0BzVXdVov/vm6BNwqTzkj5jCcnS1Ek/nEJiF573kdhuB+kEHA/1OiEeg3hwJMIqxMNjMBFbuWVKUAdFTTCx0C0eySZ0fvbgwYOHXML/DQC/27ddJivXfQAAAABJRU5ErkJggg==',
        }
    },
    methods: {
        openInvoice(data) {
            eventBus.$emit('invoiceEvent', data)
        },
        getOrders() {
            this.$store.dispatch('getOrders')
        },
        printPage() {
            // Get HTML to print from element
            const prtHtml = document.getElementById('print').innerHTML;

            // Get all stylesheets HTML
            let stylesHtml = '';
            for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                stylesHtml += node.outerHTML;
            }

            // Open the print window
            const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

            WinPrint.document.write(`<!DOCTYPE html>
            <html>
            <head>
                ${stylesHtml}
            </head>
            <body>
                ${prtHtml}
            </body>
            </html>`);

            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        },

        handleClick(tab, event) {
            // console.log(tab, event);
            eventBus.$emit('invoiceLoadEvent')
            // this.loading = true
            // setTimeout(() => {
            //     this.loading = false
            // }, 1000);
        },
    },
    computed: {
        orders() {
            return this.$store.getters.orders
        }
    },
    mounted() {
        console.log(this.image);

        this.getOrders();
    },
}
</script>
