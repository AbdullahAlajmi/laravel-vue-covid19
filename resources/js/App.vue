<template>
    <div id="map-container">
        <div id="world-map">
        </div>
        <div id="buttons" class="col-sm-12 text-center">
            <button type="button" class="btn btn-primary" @click="addCountry">Add New Country</button>
            <button type="button" class="btn btn-primary" @click="get_data_from_covid19_api(true)">Update Data (Covid19 API)</button>
        </div>
        <dialogs-wrapper></dialogs-wrapper>
    </div>

</template>


<script>

window.jQuery = require('jquery');
let $ = window.jQuery;

import Vue from 'vue'
import * as ModalDialogs from 'vue-modal-dialogs'

Vue.use(ModalDialogs)

require('./jquery-jvectormap-2.0.1.min.js');
require('./jquery-jvectormap-world-mill.js');

import {create} from 'vue-modal-dialogs'

import UpdateFormComponent from './components/UpdateFormComponent'
import AddFormComponent from './components/AddFormComponent'

const updateCountryForm = create(UpdateFormComponent, 'data')
const addCountryForm = create(AddFormComponent)

export default {

    data() {
        return {
            countries_data: null,
        }
    },
    beforeCreate() {
    },
    created() {
    },
    beforeMount() {
        this.get_data(false);
    },
    mounted() {
        this.drawMap();
    },

    methods: {
        drawMap() {
            let $vm = this;

            let map = $('#world-map').vectorMap({
                map: 'world_mill',
                zoomButtons: false,
                onRegionTipShow: function (e, el, code) {
                    try {
                        el.html('<p>' + el.html() + '</p>'
                            + 'Confirmed: ' + $vm.countries_data[code]['country_total_confirmed'] + '</br>'
                            + 'Deaths: ' + $vm.countries_data[code]['country_total_deaths'] + ' </br>'
                            + 'Recovered: ' + $vm.countries_data[code]['country_recovered'] + ' </br>');
                    } catch {
                        el.html('<p> Unknown </p>'
                            + 'Confirmed: ' + "Unknown" + '</br>'
                            + 'Deaths: ' + "Unknown" + '</br>'
                            + 'Recovered: ' + "Unknown" + '</br>');
                    }
                },
                onRegionClick: async function (e, code) {
                    try {
                        var country_update_values = await updateCountryForm({title: "Update Cases for " + $vm.countries_data[code]["country_name"],
                            country_code: code,
                            country_name: $vm.countries_data[code]["country_name"],
                            country_total_confirmed: $vm.countries_data[code]['country_total_confirmed'],
                            country_total_deaths: $vm.countries_data[code]['country_total_deaths'],
                            country_recovered: $vm.countries_data[code]['country_recovered']})

                        if (country_update_values) {
                            $vm.update_data(country_update_values);
                        }
                    } catch (err) {
                        $vm.show_message("warning", "Country does not have information, create a new country using this country code (" + code + ")")
                    }
                }
            });
        },
        get_data(requested) {
            let $vm = this;

            let uri = '/api/country';
            this.axios.get(uri).then(response => {
                $vm.countries_data = response.data;
                if (requested){
                    $vm.show_message("success", "data has been updated")
                }
            }).catch(function (error) {
                $vm.show_message("error", "Cannot connect to the server")
                console.log(error);
            });
        },
        get_data_from_covid19_api(requested) {
            let $vm = this;

            let uri = '/api/fill_data';
            this.axios.get(uri).then(response => {
                $vm.countries_data = response.data;
                if (requested){
                    $vm.show_message("success", "Updated all data from Covid19 API")
                }
            }).catch(function (error) {
                $vm.show_message("error", "Cannot connect to the server")
                console.log(error);
            });
        },
        update_data(data) {
            let $vm = this;

            let uri = '/api/country/' + data["country_code"];
            this.axios.put(uri, data).then(function (response) {
                $vm.get_data();
                $vm.show_message("success", "Updated Data for " + data["country_name"])
            }).catch(function (error) {
                if (error.response.status === 404) {
                    $vm.show_message("error", "Country code is not found")
                } else {
                    $vm.show_message("error", "Cannot connect to the server")
                }
            });
        },
        show_message(type, message) {
            if (type === "success"){
                Swal.fire({
                    icon: 'success',
                    text: message
                })
            } else if (type === "warning") {
                Swal.fire({
                    icon: 'warning',
                    text: message
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    text: message
                })
            }
        },
        async addCountry() {
            let $vm = this;

            try {
                var add_country_values = await addCountryForm()

                if (add_country_values) {
                    let uri = '/api/country/';
                    this.axios.post(uri, add_country_values).then(function (response) {
                        $vm.get_data();
                        $vm.show_message("success", "Added new country (" + add_country_values["country_name"] + ")")
                    }).catch(function (error) {
                        if (error.response.status === 409){
                            $vm.show_message("error", "Cannot add new country using existing country code.")
                        } else {
                            $vm.show_message("error", "Cannot connect to the server")
                        }
                    });
                }
            } catch (err) {
                console.log(err);
            }
        }
    }
}
</script>

<style>


#world-map {
    height: 700px;
    width: 100%;
}

.btn {
    display: inline-block;
    vertical-align: top;
}

#buttons {
    margin-top: 50px;
}

</style>
