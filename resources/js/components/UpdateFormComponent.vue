<template>
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel" ref="title">{{title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" :class="{ 'text-danger': $v.country_code.$error }">
                        <label class="control-label">Country Code</label>
                        <div>
                            <input type="text" class="form-control input-lg" ref="country_code" v-model.trim="country_code" disabled>
                            <div class="error" v-if="!$v.country_code.required">Field is required.</div>
                            <div class="error" v-if="!$v.country_code.alpha">Field must have only alphabet characters.</div>
                            <div class="error" v-if="!$v.country_code.minLength">Field must have at least {{$v.country_code.$params.minLength.min}} digit</div>
                            <div class="error" v-if="!$v.country_code.maxLength">Field must have at least {{$v.country_code.$params.maxLength.max}} digit</div>
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'text-danger': $v.country_name.$error }">
                        <label class="control-label">Country Name</label>
                        <div>
                            <input type="text" class="form-control input-lg" ref="country_name" v-model.trim="country_name">
                            <div class="error" v-if="!$v.country_name.required">Field is required.</div>
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'text-danger': $v.country_total_confirmed.$error }">
                        <label class="control-label">Total Confirmed</label>
                        <div>
                            <input type="text" class="form-control input-lg" ref="country_total_confirmed" v-model.trim="country_total_confirmed">
                            <div class="error" v-if="!$v.country_total_confirmed.required">Field is required.</div>
                            <div class="error" v-if="!$v.country_total_confirmed.numeric">Field must have only numbers</div>
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'text-danger': $v.country_total_deaths.$error }">
                        <label class="control-label">Total Deaths</label>
                        <div>
                            <input type="text" class="form-control input-lg" ref="country_total_deaths" v-model.trim="country_total_deaths">
                            <div class="error" v-if="!$v.country_total_deaths.required">Field is required.</div>
                            <div class="error" v-if="!$v.country_total_deaths.numeric">Field must have only numbers</div>
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'text-danger': $v.country_recovered.$error }">
                        <label class="control-label">Total Recovered</label>
                        <div>
                            <input type="text" class="form-control input-lg" ref="country_recovered" v-model.trim="country_recovered">
                            <div class="error" v-if="!$v.country_recovered.required">Field is required.</div>
                            <div class="error" v-if="!$v.country_recovered.numeric">Field must have only numbers</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="collectData()" :disabled="!$v.$dirty || $v.$error">Update</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Vue from 'vue'
import Vuelidate from 'vuelidate'

Vue.use(Vuelidate)

import { required, alpha, numeric, minLength , maxLength} from 'vuelidate/lib/validators'

export default {
    data () {
        return {
            title: '',
            country_code: '',
            country_name: '',
            country_total_confirmed: '',
            country_total_deaths: '',
            country_recovered: '',

            result: null
        }
    },
    beforeMount (){
        console.log("modal before mounted");
        let old_form_data = this.arguments[0];
        this.title = old_form_data.title;
        this.country_code = old_form_data.country_code;
        this.country_name = old_form_data.country_name;
        this.country_total_confirmed = old_form_data.country_total_confirmed;
        this.country_total_deaths = old_form_data.country_total_deaths;
        this.country_recovered = old_form_data.country_recovered;
        this.$v.$touch()
    },
    mounted () {
        $(this.$el).modal('show')
        $(this.$el).on('hidden.bs.modal', () => this.$close(this.result))
    },
    methods: {
        collectData() {
            this.result = {
                "country_code": this.$refs.country_code.value,
                "country_name": this.$refs.country_name.value,
                "country_total_confirmed": this.$refs.country_total_confirmed.value,
                "country_total_deaths": this.$refs.country_total_deaths.value,
                "country_recovered": this.$refs.country_recovered.value
            }
        }
    },
    validations: {
        country_code: {
            required,
            alpha,
            minLength: minLength(2),
            maxLength: maxLength(2),
        },
        country_name: {
            required,
        },
        country_total_confirmed: {
            required,
            numeric
        },
        country_total_deaths: {
            required,
            numeric
        },
        country_recovered: {
            required,
            numeric
        }
    }
}
</script>
