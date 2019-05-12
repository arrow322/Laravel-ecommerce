@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotion.add-cart-rule') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.cart-rule.store') }}" @submit.prevent="onSubmit">
            @csrf

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.promotion.add-cart-rule') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.promotion.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <cart-rule></cart-rule>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script type="text/x-template" id="cart-rule-form-template">
            <div>
                @csrf()

                <accordian :active="true" title="Information">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                            <label for="name" class="required">{{ __('admin::app.promotion.general-info.name') }}</label>

                            <input type="text" class="control" name="name" v-model="name" v-validate="'required'" value="{{ old('name') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.firstname') }}&quot;">
                            <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                            <label for="description">{{ __('admin::app.promotion.general-info.description') }}</label>

                            <textarea class="control" name="description" v-model="description" v-validate="'required'" value="{{ old('description') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.description') }}&quot;"></textarea>
                            <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('customer_groups[]') ? 'has-error' : '']">
                            <label for="customer_groups" class="required">{{ __('admin::app.promotion.general-info.cust-groups') }}</label>

                            <select type="text" class="control" name="customer_groups[]" v-model="customer_groups" v-validate="'required'" value="{{ old('customer_groups') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Customer Groups</option>
                                @foreach(app('Webkul\Customer\Repositories\CustomerGroupRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>

                            <span class="control-error" v-if="errors.has('customer_groups[]')">@{{ errors.first('customer_groups') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('channels') ? 'has-error' : '']">
                            <label for="channels" class="required">{{ __('admin::app.promotion.general-info.channels') }}</label>
                            <select type="text" class="control" name="channels[]" v-model="channels" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;" multiple="multiple">
                                <option disabled="disabled">Select Channels</option>
                                @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                    <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                @endforeach
                            </select>
                            <span class="control-error" v-if="errors.has('channels')">@{{ errors.first('channels') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('starts_from') ? 'has-error' : '']">
                            <label for="starts_from" class="required">{{ __('admin::app.promotion.general-info.starts-from') }}</label>
                            <input type="text" class="control" name="starts_from" v-model="starts_from" v-validate="'required'" value="{{ old('starts_from') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.starts-from') }}&quot;">
                            <span class="control-error" v-if="errors.has('starts_from')">@{{ errors.first('starts_from') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('ends_till') ? 'has-error' : '']">
                            <label for="ends_till" class="required">{{ __('admin::app.promotion.general-info.ends-till') }}</label>
                            <input type="text" class="control" name="ends_till" v-model="ends_till" v-validate="'required'" value="{{ old('ends_till') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.ends_till') }}&quot;">
                            <span class="control-error" v-if="errors.has('ends_till')">@{{ errors.first('ends_till') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('priority') ? 'has-error' : '']">
                            <label for="priority" class="required">{{ __('admin::app.promotion.general-info.priority') }}</label>
                            <input type="text" class="control" name="priority" v-model="priority" v-validate="'required|numeric|max:1'" value="{{ old('priority') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.priority') }}&quot;">
                            <span class="control-error" v-if="errors.has('priority')">@{{ errors.first('priority') }}</span>
                        </div>
                    </div>
                </accordian>

                <accordian :active="true" title="Conditions">
                    <div slot="body">
                        <div class="add-condition">
                            <div class="control-group" :class="[errors.has('criteria') ? 'has-error' : '']">
                                <label for="criteria" class="required">{{ __('admin::app.promotion.general-info.add-condition') }}</label>

                                <select type="text" class="control" name="criteria" v-model="criteria" v-validate="'required'" value="{{ old('channels') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.cust-groups') }}&quot;">
                                    <option value="attribute">Attribute</option>
                                    <option value="category">Category</option>
                                </select>

                                <span class="control-error" v-if="errors.has('criteria')">@{{ errors.first('criteria') }}</span>
                            </div>

                            <span class="btn btn-primary btn-lg" v-on:click="addCondition">Add Condition</span>
                        </div>

                        <div class="condition-set">
                            <!-- Attribute -->
                            <div v-for="(attr, index) in attrs" :key="index">
                                <div class="control-container mt-20">
                                    <div class="title-bar">
                                        <span>Attribute is </span>
                                        <span class="icon cross-icon" v-on:click="removeAttr(index)"></span>
                                    </div>
                                    <div class="control-group mt-10" :key="index">
                                        <select class="control" name="attributes[]" v-model="attrs[index].attribute" v-validate="'required'" title="You Can Make Multiple Selections Here" style="margin-right: 15px;">
                                            <option disabled="disabled">Select attribute</option>
                                            <option v-for="attribute in attributes" :value="attribute.id">@{{ attribute.name }}</option>
                                        </select>

                                        <select class="control" name="attributes[]" v-model="attrs[index].condition" v-validate="'required'" style="margin-right: 15px;">
                                            <option>is</option>
                                            <option>is any of</option>
                                            <option>contains</option>
                                        </select>

                                        <input type="text" class="control" name="attributes[]" v-model="attrs[index].value" placeholder="Enter Value(s)" title="Use comma for multiple values">
                                    </div>
                                </div>
                            </div>

                            <!-- category -->
                            <div v-for="(cat, index) in cats">
                                <div class="control-container mt-20">
                                    <div class="title-bar">
                                        <span>Category </span>
                                        <span class="icon cross-icon" v-on:click="removeCat(index)"></span>
                                    </div>

                                    <div class="control-group mt-15" :key="index">
                                        <select class="control" name="categories[]" v-model="cats[index].condition" v-validate="'required'" title="You Can Make Multiple Selections Here" style="margin-right: 15px;">
                                            <option>is</option>
                                            <option>is any of</option>
                                            <option>contains</option>
                                        </select>

                                        <select class="control" name="categories[]" v-model="cats[index].category" v-validate="'required'" value="{{ old('category') }}" data-vv-as="&quot;{{ __('admin::app.promotion.category') }}&quot;" multiple>
                                            <option disabled="disabled">Select Category</option>
                                            <option v-for="category in categories" :value="category.id">@{{ category.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <accordian :active="true" title="Actions">
                    <div slot="body">
                        <div class="control-group" :class="[errors.has('apply') ? 'has-error' : '']">
                            <label for="apply" class="required">Apply</label>

                            <select class="control" name="apply" v-model="apply" v-validate="'required'" value="{{ old('apply') }}" data-vv-as="&quot;Apply As&quot;">
                                <option value="1">Apply as percentage</option>
                                <option value="2">Apply as fixed Amount</option>
                                <option value="3">Adjust to percentage's value</option>
                                <option value="4">Adjust to discount value</option>
                            </select>

                            <span class="control-error" v-if="errors.has('apply')">@{{ errors.first('apply') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('disc_amt') ? 'has-error' : '']">
                            <label for="disc_amt" class="required">{{ __('admin::app.promotion.general-info.disc_amt') }}</label>
                            <input type="text" class="control" name="disc_amt" v-model="disc_amt" v-validate="'required|numeric|max:1'" value="{{ old('disc_amt') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_amt') }}&quot;">
                            <span class="control-error" v-if="errors.has('disc_amt')">@{{ errors.first('disc_amt') }}</span>
                        </div>

                        <div class="control-group" :class="[errors.has('disc_percent') ? 'has-error' : '']">
                            <label for="disc_percent" class="required">{{ __('admin::app.promotion.general-info.disc_percent') }}</label>
                            <input type="text" class="control" name="disc_percent" v-model="disc_percent" v-validate="'required|numeric|max:1'" value="{{ old('disc_percent') }}" data-vv-as="&quot;{{ __('admin::app.promotion.general-info.disc_percent') }}&quot;">
                            <span class="control-error" v-if="errors.has('disc_percent')">@{{ errors.first('disc_percent') }}</span>
                        </div>
                    </div>
                </accordian>
            </div>
        </script>

        <script>
            Vue.component('cart-rule', {
                template: '#cart-rule-form-template',

                inject: ['$validator'],

                data () {
                    return {
                        attributes: @json($criteria[0]),
                        categories: @json($criteria[1]),
                        channels: [],
                        conditions: [],
                        attrs_count: 0,
                        cats_count: 0,
                        name: '10% OFF',
                        priority: 1,
                        starts_from: 'a',
                        ends_till: 'b',
                        description: 'something',
                        customer_groups: [],
                        criteria: null,
                        attr: {
                            attribute: null,
                            condition: null,
                            value: null
                        },
                        cat: {
                            category: null,
                            condition: null,
                        },
                        attrs: [],
                        cats: []
                    }
                },

                mounted () {
                },

                methods: {
                    addCondition () {
                        if (this.criteria == 'attribute' || this.criteria == 'category') {
                            this.condition_on = this.criteria;
                        } else {
                            alert('please try again');

                            return false;
                        }

                        if (this.condition_on == 'attribute') {
                            this.attrs.push(this.attr);

                            this.attr = {
                                attribute: null,
                                condition: null,
                                value: null
                            };
                        } else if (this.condition_on == 'category') {
                            this.cats.push(this.cat);

                            this.cat = {
                                category: null,
                                condition: null
                            };
                        }
                    },

                    removeAttr(index) {
                        this.attrs.splice(index, 1);
                    },

                    removeCat(index) {
                        this.cats.splice(index, 1);
                    }
                }
            });
        </script>
    @endpush
@stop