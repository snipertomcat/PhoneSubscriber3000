<template>
    <div id="register-form" class="container">
        <div>
            <div class="row register" v-if="register_type.show &&false">
                <div class="col-sm-10 col-md-6 text-left company-register">
                    <h3>¿Eres una empresa?</h3>
                    <h3>Crea un nuevo espacio de trabajo</h3>
                    <br>
                    <button class="user-register-button-top" @click="continueCompany">Continuar</button>
                </div>
                <div class="col-sm-12 col-md-6 text-right user-register">
                    <h3>¿Eres una persona?</h3>
                    <h3>Regístrate para ver el contenido</h3>
                    <br>
                    <button class="user-register-button-top" @click="continueUser">Continuar</button>
                </div>
                <div class="col-sm-12 company-space-join">
                    <div class="col-sm-12 text-left">
                        <h3>¿Ya tienes espacio de trabajo?</h3>
                        <p>Conéctate con tu empresa.</p>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <input :class="{
                                        'col-sm-12 input rounded':true,
                                        'text-danger':register_type.dont_exist
                                    }"
                                   type="text"
                                   placeholder="URL de tu empresa"
                                   v-model="register_type.company_domain"
                                   @input="register_type.dont_exist = false">
                            <span class="text-danger" v-show="register_type.dont_exist">La empresa a la que deseas unirte aún no está en apithy.</span>
                        </div>
                        <div class="col-sm-2">
                            <h3 class="bold">.apithy.com</h3>
                        </div>
                        <div class="col-sm-2">
                            <button class="user-register-button-top" @click="continueCompanySpace">Continuar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="confirm-code-action" v-if="show_confirm_box">
                <div class="col-sm-12 confirm-code-action-inner row">
                    <div>
                        <img src="/images/sms.png"/>
                    </div>
                    <div class="col-sm-10 col-9">
                        <span class="head">Ya tengo un c&oacute;digo</span>
                        <p>He recibido un código de confirmación por SMS</p>
                        <a href="/register/confirm/phone" class="button-orange">Continuar</a>
                    </div>
                </div>

            </div>
            <div class="row register-form mt-5" v-if="register_form.show">
                <form-wizard class="col-sm-8"
                             title=""
                             subtitle=""
                             color="#FFA81E"
                             ref="formWizard"
                             errorColor="#FC2D24"
                             :validate-on-back="true"
                             stepSize="xs">

                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="false">
                        <span v-for="(errors, index) in server_errors.data" :key="index">
                            <template v-for="(e, i) in errors">
                                <strong :key="i">Error: </strong>{{e[0]}}
                            </template>
                        </span>
                        <button type="button" class="close" aria-label="Close" @click="server_errors.show = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <tab-content title="Datos" icon="" :before-change="stepValidator">
                        <form>
                            <div class="row" v-if="false">
                                <div class="col-12">
                                    <h2>Tipo de cuenta</h2>
                                    <p>Elige tu forma de uso de apithy.</p>
                                    <div class="row">
                                        <div class="col-12 col-md-4 mt-3">
                                            <div class="row justify-content-center align-items-center account-type"
                                                 :class="{'active':register_form.form_data.user_account}"
                                                 @click="setAccount('user')">
                                                <div class="col-auto">
                                                    <span><i class="fas fa-user"></i></span>
                                                </div>
                                                <div class="col-auto">
                                                    <span>Uso personal</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 mt-3">
                                            <div class="row justify-content-center align-items-center account-type"
                                                 :class="{'active':register_form.form_data.company_account}"
                                                 @click="setAccount('company')">
                                                <div class="col-auto">
                                                    <span><i class="fas fa-building"></i></span>
                                                </div>
                                                <div class="col-auto">
                                                    <span>Uso empresarial</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 mt-3">
                                            <div class="row justify-content-center align-items-center account-type"
                                                 :class="{'active':register_form.form_data.partner_account}"
                                                 @click="setAccount('partner')">
                                                <div class="text-center">
                                                    <span><i class="fas fa-hands-helping"></i></span>
                                                </div>
                                                <div class="col-auto">
                                                    <span>Aliado</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row company-account-form" v-if="register_form.form_data.company_account">
                                <div class="col-12">
                                    <h2>Nombre y Dominio de tu empresa</h2>
                                    <p>Elige el nombre y dominio que tendrá la empresa en apithy.</p>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="apithy-with-icon no-pl">
                                        <div class="input-group">
                                            <span :class="{
                                                    'input-group-text icon':true,
                                                    'text-danger': errors.has('company_name'),
                                                    }">
                                                    <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text"
                                                   maxlength="20"
                                                   name="company_name"
                                                   placeholder="Nombre de tu empresa"
                                                   :class="{
                                                           'form-control':true,
                                                           'text-danger': errors.has('company_name'),
                                                       }"
                                                   v-model="register_form.form_data.company.name"
                                                   v-validate="validators.company_name">
                                        </div>
                                        <span class="text-danger" v-show="errors.has('company_name')">
                                            {{ errors.first('company_name') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <div class="apithy-with-icon no-pl mt-3">
                                        <div class="input-group">
                                            <span :class="{
                                                    'input-group-text icon':true,
                                                    'text-danger': errors.has('domain'),
                                                    }">
                                                    <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text"
                                                   maxlength="20"
                                                   name="domain"
                                                   placeholder="Dominio de tu empresa"
                                                   :class="{
                                                       'form-control':true,
                                                       'text-danger': errors.has('domain'),
                                                   }"
                                                   v-model="register_form.form_data.company.domain"
                                                   v-validate="validators.domain">
                                            <span class="domain-url">.apithy.com</span>
                                        </div>
                                        <span class="text-danger" v-show="errors.has('domain')">
                                            {{ errors.first('domain') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row company-account-form" v-if="register_form.form_data.partner_account">
                                <div class="col-12">
                                    <h2>Nombre de tu empresa</h2>
                                    <p>Elige el nombre que tendrá la empresa en apithy.</p>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-12 col-md-6 apithy-with-icon no-pl">
                                        <div class="input-group">
                                            <span :class="{
                                                    'input-group-text icon':true,
                                                    'text-danger': errors.has('company_name'),
                                                    }">
                                                    <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text"
                                                   maxlength="20"
                                                   name="company_name"
                                                   placeholder="Nombre de tu empresa"
                                                   :class="{
                                                           'form-control':true,
                                                           'text-danger': errors.has('company_name'),
                                                       }"
                                                   v-model="register_form.form_data.company.name"
                                                   v-validate="validators.company_name">
                                        </div>
                                        <span class="text-danger" v-show="errors.has('company_name')">
                                            {{ errors.first('company_name') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-md-center mt-3">
                                <div class="col-sm-12">
                                    <h2>¿Como te llamas?</h2>
                                    <p>Así es como te verán tus compañeros en el espacio de trabajo.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 apithy-with-icon">
                                    <div class="input-group">
                                        <span :class="{
                                            'input-group-text icon':true,
                                            'text-danger': errors.has('firstname'),
                                            }">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                               maxlength="50"
                                               name="firstname"
                                               placeholder="Nombre(s)"
                                               :class="{
                                                   'col-sm-12 form-control':true,
                                                   'text-danger': errors.has('firstname'),
                                               }"
                                               v-model="register_form.form_data.user.firstname"
                                               v-validate="validators.firstname">
                                    </div>
                                    <span class="text-danger" v-show="errors.has('firstname')">{{ errors.first('firstname') }}</span>
                                </div>
                                <div class="col-sm-12 col-md-6 apithy-with-icon">
                                    <div class="input-group">
                                        <span :class="{
                                            'input-group-text icon':true,
                                            'text-danger': errors.has('lastname'),
                                            }">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                               maxlength="50"
                                               name="lastname"
                                               placeholder="Apellido(s)"
                                               :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('lastname'),
                                                   }"
                                               v-model="register_form.form_data.user.lastname"
                                               v-validate="validators.lastname">
                                    </div>
                                    <span class="text-danger" v-show="errors.has('lastname')">{{ errors.first('lastname') }}</span>
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>Configura tu contraseña</h2>
                                    <span>Debe tener al menos 6 caracteres, una mayúscula y un número</span>
                                    <div class="apithy-with-icon mt-3">
                                        <div class="input-group">
                                            <span :class="{
                                               'input-group-text icon':true,
                                               'text-danger': errors.has('password'),
                                               'text-success': samePassword && !errors.has('confirm_password')
                                                }">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password"
                                                   name="password"
                                                   placeholder="Contraseña"
                                                   ref="password"
                                                   :class="{
                                                           'col-sm-12 form-control':true,
                                                           'text-danger': errors.has('password'),
                                                           'text-success': samePassword && !errors.has('confirm_password')
                                                       }"
                                                   v-model="register_form.form_data.user.password"
                                                   v-validate="validators.pwd"
                                                   @input="passwordStrength">
                                        </div>
                                        <div class="password-strength progress">
                                            <div class="progress-bar"
                                                 role="progressbar"
                                                 ref="password_progress_bar"
                                                 :aria-valuenow="password_strength"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="text-danger" v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                    </div>
                                    <br>
                                    <div class="apithy-with-icon">
                                        <div class="input-group">
                                            <span :class="{
                                                   'input-group-text icon':true,
                                                   'text-danger': errors.has('confirm_password'),
                                                   'text-success': samePassword && !errors.has('confirm_password')
                                                }">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password"
                                                   name="confirm_password"
                                                   placeholder="Confirmar contraseña"
                                                   :class="{
                                                           'col-sm-12 form-control':true,
                                                           'text-danger': errors.has('confirm_password'),
                                                           'text-success': samePassword && !errors.has('confirm_password')
                                                       }"
                                                   v-model="register_form.form_data.user.password_confirmation"
                                                   v-validate="validators.pwd_confirmation">
                                        </div>
                                        <span class="text-danger" v-show="errors.has('confirm_password')">{{ errors.first('confirm_password') }}</span>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="row justify-content-md-center">
                                <div class="col-sm-12">
                                    <h2>Datos de contacto</h2>
                                    <span>Regístrate con tu correo, tu teléfono celular o con ambos</span><br><br>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="input-group">
                                                <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('email'),
                                                        }">
                                                        <i class="fas fa-envelope"></i>
                                                </span>
                                                <input type="email"
                                                       name="email"
                                                       ref="email"
                                                       placeholder="Correo electrónico"
                                                       :class="{
                                                               'col-sm-12 form-control':true,
                                                               'text-danger': errors.has('email'),
                                                           }"
                                                       v-validate="validators.email"
                                                       v-model="register_form.form_data.user.refer_email">
                                            </div>
                                            <span class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="input-group">
                                                <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('confirm_email'),
                                                    }">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <input type="email"
                                                       name="confirm_email"
                                                       placeholder="Confirmar correo electrónico"
                                                       :class="{
                                                               'col-sm-12 form-control':true,
                                                               'text-danger': errors.has('confirm_email'),
                                                           }"
                                                       v-model="register_form.form_data.user.email"
                                                       v-validate="validators.email_confirmation">
                                            </div>
                                            <span class="text-danger" v-show="errors.has('confirm_email')">{{ errors.first('confirm_email') }}</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-md-left" v-if="register_form.form_data.contact_preferences !== 'sms'">
                                        <div class="col-4 col-md-2">
                                            <select class="form-control phone-code"
                                                    name="phone_code"
                                                    v-model="register_form.form_data.user.phone_code">
                                                <option v-for="code in phone_code_list"
                                                        :value="code.value">
                                                    +{{code.value}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-8 col-md-4 apithy-with-icon">
                                            <div class="input-group">
                                                <span :class="{
                                                       'input-group-addon icon':true,
                                                       'text-danger': errors.has('phone'),
                                                    }">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <input type="text"
                                                       ref="phone"
                                                       name="phone"
                                                       maxlength="10"
                                                       placeholder="Número celular"
                                                       :class="{
                                                           'form-control':true,
                                                           'text-danger': errors.has('phone'),
                                                       }"
                                                       v-model="register_form.form_data.user.phone"
                                                       @keypress="isNumber"
                                                       v-validate="validators.registered_phone">
                                            </div>
                                            <span class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
                                        </div>
                                        <div class="col-12 col-md-6 apithy-with-icon">
                                            <div class="input-group">
                                                <span :class="{
                                                       'input-group-addon icon':true,
                                                       'text-danger': errors.has('confirm_phone'),
                                                    }">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <input type="text"
                                                       name="confirm_phone"
                                                       maxlength="10"
                                                       placeholder="Confirmar número celular (10 dígitos)"
                                                       :class="{
                                                           'form-control':true,
                                                           'text-danger': errors.has('confirm_phone'),
                                                       }"
                                                       v-model="register_form.form_data.user.confirm_phone"
                                                       @keypress="isNumber"
                                                       v-validate="validators.confirm_registered_phone">
                                            </div>
                                            <span class="text-danger" v-show="errors.has('confirm_phone')">{{ errors.first('confirm_phone') }}</span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-left" v-else>
                                        <div class="col-4 col-md-2">
                                            <select class="form-control phone-code"
                                                    name="phone_code"
                                                    v-model="register_form.form_data.user.phone_code">
                                                <option v-for="code in phone_code_list"
                                                        :value="code.value">
                                                    +{{code.value}}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-8 col-md-4 apithy-with-icon">
                                            <div class="input-group">
                                                <span :class="{
                                                       'input-group-addon icon':true,
                                                       'text-danger': errors.has('phone'),
                                                    }">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <input type="text"
                                                       ref="phone"
                                                       name="phone"
                                                       maxlength="10"
                                                       placeholder="Número celular"
                                                       :class="{
                                                           'form-control':true,
                                                           'text-danger': errors.has('phone'),
                                                       }"
                                                       v-model="register_form.form_data.user.phone"
                                                       @keypress="isNumber"
                                                       data-vv-as="Teléfono"
                                                       v-validate="validators.phone">
                                            </div>
                                            <span class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
                                        </div>
                                        <div class="col-12 col-md-6 apithy-with-icon">
                                            <div class="input-group">
                                                <span :class="{
                                                       'input-group-addon icon':true,
                                                       'text-danger': errors.has('confirm_phone'),
                                                    }">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <input type="text"
                                                       name="confirm_phone"
                                                       maxlength="10"
                                                       placeholder="Confirmar número celular (10 dígitos)"
                                                       :class="{
                                                           'form-control':true,
                                                           'text-danger': errors.has('confirm_phone'),
                                                       }"
                                                       v-model="register_form.form_data.user.confirm_phone"
                                                       @keypress="isNumber"
                                                       v-validate="validators.confirm_phone">
                                            </div>
                                            <span class="text-danger" v-show="errors.has('confirm_phone')">{{ errors.first('confirm_phone') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-12">
                                    <h2>Elige tu forma de contacto</h2>
                                </div>
                                <div class="col-12 col-sm-4 mt-3">
                                    <div class="contact-preference"
                                         :class="{'active':register_form.form_data.contact_preferences === 'mail'}"
                                         @click="setContactPreference('mail')">
                                        <div class="text-center">
                                            <span><i class="fas fa-at"></i></span>
                                            <span>Email</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 mt-3">
                                    <div class="contact-preference"
                                         :class="{'active':register_form.form_data.contact_preferences === 'sms'}"
                                         @click="setContactPreference('sms')">
                                        <div class="text-center">
                                            <span><i class="fas fa-phone"></i></span>
                                            <span>SMS</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4 mt-3" v-if="false">
                                    <div class="contact-preference"
                                         :class="{'active':register_form.form_data.contact_preferences === 'whatsapp'}"
                                         @click="setContactPreference('whatsapp')">
                                        <div class="text-center">
                                            <span><i class="fab fa-whatsapp"></i></span>
                                            <span>Whatsapp</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </tab-content>

                    <div slot="footer" slot-scope="props">
                        <div class="row align-items-center">
                            <div class="col-2 col-md-1">
                                <input type="checkbox" class="form-control width-70" v-model="legal.privacity.accepted">
                            </div>
                            <div class="col-10 col-md-11">
                                <span>
                                    Al elegir "Aceptar" otorgas tu consentimiento sobre el <a data-fancybox
                                                                                    :data-src="legal.privacity.id"
                                                                                    href="javascript:;">{{ legal.privacity.label }}</a>.
                                </span>
                            </div>
                        </div>
                        <div class="row align-items-center" v-show="!register_form.form_data.partner_account">
                            <div class="col-2 col-md-1">
                                <input type="checkbox" class="form-control width-70" v-model="legal.customer_services.accepted">
                            </div>
                            <div class="col-10 col-md-11">
                                <span>
                                    Al elegir "Aceptar" comprendes y aceptas las <a data-fancybox
                                                                                    :data-src="legal.customer_services.id"
                                                                                    href="javascript:;">{{ legal.customer_services.label }}</a>.
                                </span>
                            </div>
                        </div>
                        <div class="row align-items-center" v-show="register_form.form_data.company_account">
                            <div class="col-2 col-md-1">
                                <input type="checkbox" class="form-control width-70" v-model="legal.company_services.accepted">
                            </div>
                            <div class="col-10 col-md-11">
                                <span>
                                    Al elegir "Aceptar" comprendes y aceptas los <a data-fancybox
                                                                                    :data-src="legal.company_services.id"
                                                                                    href="javascript:;">{{ legal.company_services.label }}</a>.
                                </span>
                            </div>
                        </div>
                        <div class="row align-items-center" v-show="register_form.form_data.partner_account">
                            <div class="col-2 col-md-1">
                                <input type="checkbox" class="form-control width-70" v-model="legal.partner_services.accepted">
                            </div>
                            <div class="col-10 col-md-11">
                                <span>
                                    Al elegir "Aceptar" comprendes y aceptas los <a data-fancybox
                                                                                    :data-src="legal.partner_services.id"
                                                                                    href="javascript:;">{{ legal.partner_services.label }}</a>.
                                </span>
                            </div>
                        </div>
                        <div class="row justify-content-md-center">
                            <div class="col-sm-10">
                                <div class="continue-btn">
                                    <wizard-button :disabled="accepted_legal_terms" @click.native="finishRegister">
                                        Aceptar
                                    </wizard-button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form-wizard>

                <div class="col-md-4 contenedor-persona-apithy">
                    <div class="row social-connect mt-3">
                        <div class="col-sm-8 col-md-10 block-centered">
                            <h3 class="text-center mb-2">Social connect</h3>
                        </div>
                        <div class="col-sm-8 col-md-10 block-centered">
                            <a href="/social/login/google"
                               class="social-login-item google-login-button">
                            </a>
                        </div>
                        <div class="col-sm-8 col-md-10 block-centered">
                            <a href="/social/login/facebook"
                               class="social-login-item facebook-login-button">
                            </a>
                        </div>
                        <div class="col-sm-8 col-md-10 block-centered">
                            <a href="/social/login/linkedin"
                               class="social-login-item linkedin-login-button">
                            </a>
                        </div>
                        <div class="legal-legend col-sm-8 col-md-10 block-centered">
                            Al continuar, declaras que aceptas nuestros<br>
                            <a target="_blank" href="https://www.apithy.com/terminos-y-condiciones">
                                Términos y condiciones de uso</a> y nuestro
                            <a target="_blank" href="https://www.apithy.com/acuerdo-de-servicio">Aviso de Privacidad</a>.
                        </div>
                    </div>
                    <img v-bind:src="registerImage" class="persona-apithy" alt="apithy-cursos-online">
                </div>

            </div>

            <div class="row justify-content-md-center register" v-if="register_email_resend.show">
                <div class="col-sm-12">
                    <img src="/images/resources/svg/estasAUnPaso.svg" alt="" class="img-responsive one-step">
                </div>
                <div class="col-sm-10 mt-3">
                    <h2>Est&aacute;s a un paso de ser parte de Apithy</h2>
                    <div class="" v-if="getContactPreference() === 'mail'">
                        <p>
                            Hemos enviado un correo a <b>"{{ user.email }}"</b>.
                            <br>Da clic en el enlace para continuar el proceso.
                        </p>
                        <div class="btn btn-link" @click="showRegister('wrong')" v-if="wrong.counter < 2">
                            ¿Este no es tu correo?
                        </div>
                        <br>
                        <div v-if="register_email_resend.resend < 3">
                            <button class="btn btn-link" :disabled="!(register_email_resend.timer === 0)" @click="resend">
                                Reenviar correo de verificación
                            </button>
                            <br>
                            <span v-if="register_email_resend.timer > 0">
                                Tienes que esperar {{ register_email_resend.timer }} segundos antes de que te podamos enviar otro correo electrónico.
                            </span>
                        </div>
                    </div>
                    <div class="" v-else>
                        <p>
                            Hemos enviado un código de 4 dígitos por SMS para validar tu número <br>celular <b>{{ user.phone }}</b>.
                            Ingresa el código a continuación.
                        </p>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-3 col-md-2">
                                <input class="form-control confirmation-code-input"
                                       :class="register_email_resend.class"
                                       id="one"
                                       ref="one"
                                       type="text"
                                       maxlength="1"
                                       data-next="two"
                                       v-model="register_email_resend.digits_code.one"
                                       @keypress="isNumber"
                                       @keyup="focusNext"
                                       @click="select">
                            </div>
                            <div class="col-3 col-md-2">
                                <input class="form-control confirmation-code-input"
                                       :class="register_email_resend.class"
                                       id="two"
                                       ref="two"
                                       type="text"
                                       maxlength="1"
                                       data-next="three"
                                       v-model="register_email_resend.digits_code.two"
                                       @keypress="isNumber"
                                       @keyup="focusNext"
                                       @click="select">
                            </div>
                            <div class="col-3 col-md-2">
                                <input class="form-control confirmation-code-input"
                                       :class="register_email_resend.class"
                                       id="three"
                                       ref="three"
                                       type="text"
                                       maxlength="1"
                                       data-next="four"
                                       v-model="register_email_resend.digits_code.three"
                                       @keypress="isNumber"
                                       @keyup="focusNext"
                                       @click="select">
                            </div>
                            <div class="col-3 col-md-2">
                                <input class="form-control confirmation-code-input"
                                       :class="register_email_resend.class"
                                       id="four"
                                       ref="four"
                                       type="text"
                                       maxlength="1"
                                       data-next="four"
                                       v-model="register_email_resend.digits_code.four"
                                       @keypress="isNumber"
                                       @keyup="focusNext"
                                       @click="select">
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center" v-if="register_email_resend.message">
                            <div class="col-6">
                                <div class="alert-body" :class="register_email_resend.class">
                                    {{ register_email_resend.message }}
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="btn btn-link" @click="showRegister('wrong')" v-if="wrong.counter < 2">
                            ¿Este no es tu número teléfono celular?
                        </div>
                        <br>
                        <div v-if="register_email_resend.resend < 3">
                            <button class="btn btn-link" :disabled="!(register_email_resend.timer === 0)" @click="resend">
                                Reenviar sms de verificación
                            </button>
                            <br>
                            <span v-if="register_email_resend.timer > 0">
                                Tienes que esperar {{ register_email_resend.timer }} segundos antes de que te podamos enviar otro sms.
                            </span>
                        </div>
                    </div>
                </div>
                <br><br>
            </div>

            <div class="row justify-content-md-center register" v-if="wrong.show">
                <div class="col-sm-12">
                    <img src="/images/resources/svg/estasAUnPaso.svg" alt="" class="img-responsive one-step">
                </div>
                <div class="col-sm-10 mt-3">
                    <h2>Est&aacute;s a un paso de ser parte de Apithy</h2>

                    <div class="row">
                        <div class="col-12">
                            <p v-if="wrong.phone">Ingresa tu número de teléfono celular.</p>
                            <p v-else>Ingresa tu correo electrónico.</p>
                        </div>
                    </div>

                    <div class="" v-if="wrong.phone">
                        <div class="row justify-content-center">
                            <div class="col-8 apithy-with-icon">
                                <div class="input-group">
                                    <span :class="{'input-group-addon icon':true, 'text-danger': errors.has('new_phone')}">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text"
                                           ref="new_phone"
                                           name="new_phone"
                                           maxlength="10"
                                           placeholder="Número celular (10 dígitos)"
                                           :class="{'form-control':true, 'text-danger': errors.has('new_phone')}"
                                           v-model="wrong.new_phone"
                                           v-validate="validators.new_phone"
                                           @keypress="isNumber">
                                </div>
                                <span class="text-danger" v-show="errors.has('new_phone')">{{ errors.first('new_phone') }}</span>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-8 apithy-with-icon">
                                <div class="input-group">
                                    <span :class="{'input-group-addon icon':true, 'text-danger': errors.has('confirm_new_phone')}">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text"
                                           name="confirm_new_phone"
                                           maxlength="10"
                                           placeholder="Confirmar número celular (10 dígitos)"
                                           :class="{'form-control':true, 'text-danger': errors.has('confirm_new_phone')}"
                                           v-model="wrong.confirm_new_phone"
                                           v-validate="validators.confirm_new_phone"
                                           data-vv-validate-on="change|input|keypress|focus|blur"
                                           @keypress="isNumber">
                                </div>
                                <span class="text-danger" v-show="errors.has('confirm_new_phone')">{{ errors.first('confirm_new_phone') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="" v-else>
                        <div class="row justify-content-center">
                            <div class="col-8 apithy-with-icon">
                                <div class="input-group">
                                    <span :class="{'input-group-text icon':true,'text-danger': errors.has('new_email')}">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email"
                                           name="new_email"
                                           ref="new_email"
                                           placeholder="Correo electrónico"
                                           :class="{'col-sm-12 form-control':true, 'text-danger': errors.has('new_email')}"
                                           v-validate="validators.new_email"
                                           v-model="wrong.new_email">
                                </div>
                                <span class="text-danger" v-show="errors.has('new_email')">{{ errors.first('new_email') }}</span>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-8 apithy-with-icon">
                                <div class="input-group">
                                    <span :class="{
                                           'input-group-text icon':true,
                                           'text-danger': errors.has('confirm_new_email'),
                                        }">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email"
                                           name="confirm_new_email"
                                           placeholder="Confirmar correo electrónico"
                                           :class="{'col-sm-12 form-control':true, 'text-danger': errors.has('confirm_new_email')}"
                                           v-model="wrong.confirm_new_email"
                                           v-validate="validators.confirm_new_email"
                                           data-vv-validate-on="change|input|keypress|focus|blur">
                                </div>
                                <span class="text-danger" v-show="errors.has('confirm_new_email')">{{ errors.first('confirm_new_email') }}</span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <div class="row justify-content-between">
                                <div class="col-12 col-md-4 mt-3 text-left">
                                    <div class="btn btn-lg override-btn width-100" @click="backToResend">
                                        <span class="font-25"><i class="fa fa-angle-left"></i></span>
                                        <span>Regresar</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mt-3">
                                    <button class="btn btn-lg btn-primary-alter width-100"
                                            :disabled="errors.any()"
                                            @click="updateConfirmationInfo">
                                        Confirmar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>
                <br><br>
            </div>

            <div class="row justify-content-center register" v-if="register_welcome.show">
                <div class="col-sm-12">
                    <img src="/images/resources/svg/ready.svg" alt="" class="img-responsive one-step">
                </div>
                <div class="col-sm-12 mt-3">
                    <h2>¡Bienvenido a Apithy!</h2>
                    <p>Tu identidad ha sido validada. ¡Ingresa ahora!</p>
                </div>
                <div class="col-12">
                    <div class="row justify-content-center mt-3">
                        <div class="col-12 col-md-6 apithy-with-icon">
                            <div class="input-group">
                                <span :class="{
                                    'input-group-text icon':true,
                                    'text-danger': errors.has('login_user'),
                                    }">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text"
                                       name="login_user"
                                       placeholder="Correo electrónico o número celular"
                                       :class="{
                                                   'col-sm-12 form-control':true,
                                                   'text-danger': errors.has('login_user'),
                                               }"
                                       v-model="access_data.access"
                                       v-validate="validators.login.email">
                            </div>
                            <span class="text-danger" v-show="errors.has('login_user')">{{ errors.first('login_user') }}</span>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-12 col-md-6 apithy-with-icon">
                            <div class="input-group">
                                <span :class="{
                                    'input-group-text icon':true,
                                    'text-danger': errors.has('login_pass'),
                                    }">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password"
                                       name="login_pass"
                                       :placeholder="$t('messages.password')"
                                       :class="{
                                                   'col-sm-12 form-control':true,
                                                   'text-danger': errors.has('login_pass'),
                                               }"
                                       v-model="access_data.password"
                                       v-validate="validators.login.password"
                                       data-vv-validate-on="change|input|keypress|focus|blur" @keypress.enter="login">
                            </div>
                            <span class="text-danger" v-show="errors.has('login_pass')">{{ errors.first('login_pass') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row justify-content-center action-buttons mt-3">
                        <div class="col-6 col-lg-3">
                            <button class="btn btn-lg btn-primary-alter width-100" @click="login" :disabled="login_validate">
                                <i class="arrow circle right icon"></i>
                                {{ $t('messages.login') }}
                            </button>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3" v-if="register_welcome.message">
                        <div class="col-6">
                            <div class="alert alert-danger" role="alert">
                                <button type="button" class="close" @click="register_welcome.message = null" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Error: </strong> {{ register_welcome.message }}
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3" v-if="register_welcome.loading">
                        <div class="col-auto">
                            <div class="loader"></div>
                        </div>
                    </div>
                    <div class="row justify-content-center remember-buttons mt-3">
                        <div class="col-12">
                            <a href="/password/reset"
                               class="is-primary tex-center block">
                                {{ $t('messages.forgot_password') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>

            <div style="display: none;" id="terms_conditions">
                <div class="container legal">
                    <div class="row">
                        <div class="col-12 text-center mt-3">
                            <h2>
                                Apithy: PLATAFORMA DE SERVICIOS DE GESTIÓN DE APRENDIZAJE.
                            </h2>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3><u><b>Condiciones de servicio al usuario</b></u></h3>
                            <p>Fecha efectiva: 1 de noviembre de 2018</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-justify">
                            <p class="ident-50">
                                Las siguientes <b>Condiciones de Servicio al Usuario</b> (en adelante, simplemente como
                                las <b>“Condiciones”</b>),
                                rigen tu acceso y el uso que das a la plataforma de nuestro sistema de gestión de
                                aprendizaje (la
                                <b>“Plataforma”</b>), así como a las herramientas, experiencias y retos incluidas en
                                ella (en lo
                                sucesivo, como
                                los <b>“Servicios”</b>). Por favor, léelas detenidamente, ya que los términos que aquí
                                se plasman se
                                aplican a
                                ti en calidad de <b>Usuario</b> de tales <b>Servicios</b>. Si representas a una empresa
                                u otra entidad
                                legal, al
                                aceptar las <b>Condiciones</b> declaras bajo protesta de decir verdad, que tienes la
                                representación
                                debida para
                                vincular legalmente a la compañía y/o entidad legal, y como tal, cualquier uso que des
                                lo efectuaras en
                                calidad de representante legal de la misma.
                            </p>
                            <p class="font-18"><b>Sobre el alcance legal.</b></p>
                            <p class="ident-50">
                                La <b>Plataforma</b> y todo su contenido, son propiedad de <b>Geekstadium, S. A. P. I de
                                C. V.</b> (en
                                adelante, como
                                <b>“Apithy”</b>) Las <b>Condiciones</b> aquí plasmadas son jurídicamente vinculantes y,
                                por lo tanto, se
                                entienden e
                                interpretan como un contrato entre tú y nosotros. Como parte de estas <b>Condiciones</b>,
                                aceptas y
                                otorgas tu
                                consentimiento para cumplir con la versión más reciente de nuestra <b>Política de usos
                                adecuados</b>,
                                incorporada como referencia en estas <b>Condiciones</b>. Si utilizas los
                                <b>Servicios</b> o accedes a
                                ellos, o si
                                continúas accediendo o utilizando <b>Servicios</b> después de recibir una notificación
                                de un cambio en
                                estos
                                términos o la <b>Política de usos adecuados</b>, confirmas que has leído, comprendido y
                                aceptado
                                obligarte
                                respecto de ellas. <b>"Nosotros"</b>, "nuestro/a(s)" y "nos", se refieren actual e
                                indistintamente a la
                                entidad
                                de <b>Apithy</b> aplicable en el Contrato.
                            </p>
                            <p class="ident-50">
                                En tu calidad de <b>Usuario</b> y/o <b>Cliente</b>, garantizas desde ahora que
                                mantendrás asegurada la
                                contraseña que
                                designes para tener acceso a los servicios, quedando prohibido proporcionarla a
                                cualquier tercero que no
                                tenga la calidad fidedigna de <b>Usuario</b>.
                            </p>
                            <p class="font-18"><b>Acerca de las preferencias e instruccione del cliente</b></p>
                            <p class="ident-50">
                                O bien eres un <b>Usuario</b> autorizado de un espacio controlado por un <b>Cliente</b>,
                                o bien, eres el
                                <b>Cliente</b>
                                mismo. Por lo tanto, tu calidad de <b>Usuario</b> es consecuencia de la contratación que
                                ha(s) llevado a
                                cabo,
                                o dicha contratación la ha llevado a cabo una organización o tercero al que nos
                                referimos a lo largo de
                                estos términos como <b>Cliente, y quien ha determinado la gama de los Servicios a los
                                que tendrás
                                acceso.</b>
                                 En consecuencia, al usar la <b>Plataforma</b> es debido a la contratación individual
                                que has hecho, o
                                bien,
                                por qué estás siguiendo instrucciones de tu empleador; en este último caso, el <b>Cliente</b>
                                es tu
                                empleador.
                            </p>
                            <p class="ident-50">
                                Lo anterior significa que el <b>Cliente</b> – seas tú como tal o cualquier tercero-
                                ha(n) aceptado y
                                otorgado
                                su consentimiento por separado respecto nuestras <b>Condiciones de Servicio al
                                Cliente</b>, o bien ha(s)
                                celebrado por escrito un acto jurídico con nosotros (en cualquier de estos casos,
                                identificado(s) como
                                el <b>"Contrato"</b>), lo que le(te) permitió crear y configurar los <b>Servicios</b> y
                                experiencias a
                                las que tendrás
                                acceso. De tal modo que tú y otras personas puedan utilizarlos (cada una con acceso
                                fidedigno, incluido
                                tú, a quienes se denominará en lo particular y sucesivo como  <b>"Usuario
                                autorizado”</b>).
                            </p>
                            <p class="ident-50">
                                Cuando tu empleador es quien celebra el contrato con nosotros, los <b>Servicios</b>
                                incluyen la facultad
                                de
                                autorizar después a más <b>Usuarios</b> para que se unan a su equipo o a sus equipos.
                                Cuando un <b>Usuario</b>
                                <b>autorizado</b> (incluido tú) envía contenido o información a los Servicios, tales
                                como mensajes o
                                archivos
                                (<b>“Datos del Cliente”</b>), reconocen y aceptan que tales <b>Datos</b> son propiedad
                                del
                                <b>Cliente</b> y que el <b>Contrato</b>
                                previamente celebrado ofrece muchas opciones y control sobre los mismos. Por ejemplo, el
                                <b>Cliente</b>
                                puede
                                proporcionar o denegar el acceso a los <b>Servicios</b>; activar o desactivar las
                                integraciones de
                                terceros,
                                gestionar las autorizaciones y los ajustes, transferir o asignar equipos, compartir
                                experiencias o unir
                                tu equipo o experiencias a otros equipos o experiencias; por lo que estas opciones e
                                instrucciones
                                pueden afectar al acceso, la utilización, la divulgación, la modificación o la
                                eliminación de ciertos
                                <b>Datos del cliente</b>.
                            </p>
                            <p class="font-18"><b>La relación entr tú, el cliente y nosotros.</b></p>
                            <p class="ident-50">
                                Por lo que respecta a la relación y/o vinculación entre nosotros y el <b>Cliente</b>,
                                aceptas y
                                reconoces desde
                                el momento que utilizas los <b>Servicios</b> que, es exclusiva y únicamente
                                responsabilidad del <b>Cliente</b>:
                                (a)
                                informarte a ti y a los demás <b>Usuarios autorizados</b> de cualquier política y
                                práctica relevante
                                relacionada con él, así como de cualquier ajuste que pueda afectar al procesamiento de
                                sus <b>Datos</b>;
                                (b)
                                obtener cualquier derecho, autorización o consentimiento de los <b>Usuarios
                                autorizados</b> que sea
                                necesario
                                para el uso legal de los <b>Datos del cliente</b> y la gestión de los <b>Servicios</b>;
                                (c) asegurar que
                                la
                                transferencia y procesamiento de los <b>Datos del Cliente</b> conforme al
                                <b>Contrato</b> se realizan de
                                forma lícita,
                                y; (d) responder y resolver cualquier conflicto con un <b>Usuario</b> autorizado en
                                relación con o en
                                base a
                                los <b>Datos del Cliente</b>, los <b>Servicios</b> o la incapacidad de éste para cumplir
                                con estas
                                obligaciones. En
                                consecuencia, <b>Apithy</b> no se hace responsable ni ofrece ningún tipo de garantía, ni
                                expresa ni
                                implícita,
                                en relación con los <b>Servicios</b> que se te proporcionan, con base en la condición
                                actual y a su
                                disponibilidad.
                            </p>
                            <p class="font-18"><b>Algunas reglas básicas.</b></p>
                            <p class="ident-50">
                                <u><b>Debes ser mayor de edad.-</b></u> Excepto, hasta el punto que lo prohíban las
                                leyes aplicables,
                                los Servicios no están dirigidos a menores de dieciocho años, por lo que está prohibido
                                que los utilice
                                cualquier persona por debajo de esta edad. Por lo tanto, en este acto declaras y
                                manifiestas bajo
                                portesta de decir verdad que, por lo menos tienes dieciocho años y que eres la persona a
                                la que se le
                                proporciona el Servicio con motivo de, la contratación hecha por tu empleador en calidad
                                de
                                <b>Cliente</b>
                                respecto de los mismos, o bien, por la contratación que en lo personal has decidido
                                efectuar.
                            </p>
                            <p class="ident-50">
                                Se entiende de manera tácita que nadie puede utilizar los <b>Servicios</b> y/o las
                                experiencias, ni
                                acceder a
                                ellos, cualquiera que sea la finalidad, si alguna de las declaraciones y/o
                                manifestaciones anteriores no
                                son ciertas y fidedignas.
                            </p>
                            <p class="ident-50">
                                <u><b>Mientras estés aquí, debes cumplir las reglas.-</b></u> Para garantizar un entorno
                                de trabajo
                                seguro y productivo, todos los <b>Usuarios autorizados</b> deben cumplir nuestra <b>Política
                                de usos
                                adecuados</b>, 
                                debiendo estar atentos, a fin de informar de cualquier contenido o comportamiento
                                inadecuado
                                del que puedas percatarte y que ocasione un perjuicio o daño para el <b>Cliente</b> y/o
                                nosotros.
                            </p>
                            <p class="ident-50">
                                <u><b>Eres Usuario por tu propio derecho, a discreción del Cliente (y/o a la
                                    nuestra).-</b></u> Estas
                                <b>Condiciones</b> para el <b>Usuario</b> permanecerán en vigor hasta que la
                                contratación que celebramos
                                contigo
                                (cuando así aplique) y/o el <b>Cliente</b> concluya, o bien, hasta que el <b>Cliente</b>
                                o nosotros
                                cancelemos tu
                                acceso a los <b>Servicios</b>. Cuando así corresponda, ponte en contacto con el <b>Cliente</b>
                                si en
                                algún momento o
                                por alguna razón deseas cancelar tu cuenta.
                            </p>
                            <p class="font-18"><b>Limitación de responsabilidad.</b></p>
                            <p class="ident-50">
                                Si consideramos que ocurre una violación de tu parte, ya sea al <b>Contrato</b>, a las
                                <b>Condiciones
                                    del usuario</b>,
                                a la <b>Política de usos adecuados</b>, o de cualquier otra política, en la mayoría de
                                los casos
                                pediremos al
                                <b>Cliente</b> que lleve a cabo medidas correctivas en vez de intervenir nosotros. Sin
                                perjuicio de lo
                                anterior, puede que intervengamos y realicemos lo que nos parezca la acción más
                                apropiada (lo que
                                incluye desactivar tu cuenta) si el <b>Cliente</b> omite realizar dicha acción; o bien
                                intervendremos si
                                consideramos que existe un riesgo inminente de dañar la <b>Plataforma</b>, y/o a
                                nosotros, y/o a los <b>Servicios</b>,
                                y/o a los <b>Usuarios autorizados</b>, y/o a terceros que tengan la calidad de nuestros
                                socios
                                comerciales. En
                                caso de que tengamos que desactivar tu cuenta con motivo de las violaciones antes
                                citadas, o bien, el
                                Cliente solicitara que se desactive, no tendrás ninguna responsabilidad, en ningún caso,
                                de ningún
                                beneficio o ingreso perdido, ni de ningún daño indirecto, especial, accidental,
                                consiguiente, de
                                cobertura o punitivo, sea cual fuere la causa, ya sea por <b>Contrato</b>, agravio o
                                bajo cualquier otro
                                principio de responsabilidad. Sin embargo, si la desactivación de tu cuenta procede por
                                actividad
                                ilegal, ilegítima o por haber ocasionado un perjuicio directo a nosotros, al
                                <b>Cliente</b> o cualquier
                                tercero, tendremos plena facultad para emprender las acciones legales que adjudiquen la
                                responsabilidad
                                monetaria debida precisamente por daños y además perjucios ocasionados con motivos de la
                                infracción a
                                estas <b>Condiciones del usuario</b>.
                            </p>
                            <p class="font-18"><b>Vigencia.</b></p>
                            <p class="ident-50">
                                Las <b>Condiciones</b> aquí vertidas serán válidas y estarán en vigor mientras continues
                                con la calidad
                                de
                                <b>Usuario</b>, sea cual sea el motivo por el cual la ostentes, y en tanto no te
                                notifiquemos la
                                actualización
                                y/o el cambio en las mismas. Las secciones tituladas <b>"La relación entre tú, el
                                Cliente y
                                nosotros"</b>,
                                <b>“Cláusulas generales”</b>, así como la <b>"Limitación de responsabilidad"</b>,
                                seguirán vigentes
                                incluso en caso de
                                que se produzca cualquier término o expiración de las <b>Condiciones</b>, el
                                <b>Contrato</b> o bien
                                concluya tu
                                calidad de <b>Usuario</b>.
                            </p>
                            <p class="font-18"><b>Cláusulas generales.</b></p>
                            <p class="ident-50">
                                <u><b>Correo electrónico y mensajes de Apithy.-</b></u> Salvo que se establezca aquí lo
                                contrario, todas
                                las notificaciones bajo las <b>Condiciones</b> se producirán por correo electrónico,
                                aunque podamos
                                elegir
                                notificar a los <b>Usuarios autorizados</b> mediante los <b>Servicios</b> (por ejemplo,
                                con una
                                notificación en la
                                plataforma) en su lugar. Las notificaciones que tu desees realizar a <b>Apithy</b> deben
                                enviarse
                                enviarse
                                mediante el chat interno habilitado en tu cuenta; salvo las de carácter legal que deben
                                enviarse
                                a <a href="mailto:info@apithy.com">info@apithy.com</a>. Se considerará que una
                                notificación se ha
                                entregado debidamente, (a) el día después
                                de enviarse, en el caso de enviarse por correo electrónico; y (b) el mismo día, en el
                                caso de
                                notificaciones enviadas mediante los <b>Servicios</b>. Las notificaciones bajo el <b>Contrato</b>
                                se
                                enviarán
                                exclusivamente a quienes ostentan la calidad de <b>Cliente</b>, de conformidad con los
                                términos de dicho
                                acuerdo.
                            </p>
                            <p class="ident-50">
                                <u><b>Aviso y política de privacidad.-</b></u> Por favor, revisa nuestro <b>Aviso y de
                                privacidad</b> para
                                conocer más información sobre cómo recopilamos y utilizamos los datos personales
                                relacionados con la
                                utilización y al funcionamiento de nuestros productos y servicios.
                            </p>
                            <p class="ident-50">
                                <u><b>Modificaciones.-</b></u> A medida que evolucione nuestro negocio, puede que
                                cambiemos estas
                                <b>Condiciones</b> o la <b>Política de usos aceptables</b>. Si realizamos un cambio
                                sustancial en las
                                <b>Condiciones</b> o
                                en la <b>Política de usos aceptables</b>, notificaremos de la forma que nos resulte más
                                expedita y antes
                                de que
                                el cambio tenga efecto, ya sea escribiendo a la dirección de correo asociada a tu cuenta
                                o enviándote un
                                mensaje mediante los <b>Servicios</b>. Puedes revisar la versión más reciente de las <b>Condiciones</b>
                                cuando lo
                                desees, visitando esta página, y las siguientes para las versiones más recientes de las
                                otras cuestiones
                                legales a las que hacemos referencia: la <b>Política de usos aceptables</b> y el <b>Aviso
                                de
                                privacidad</b>. Toda
                                revisión sustancial de estas <b>Condiciones</b> se hará efectiva en la fecha indicada en
                                nuestra
                                notificación,
                                y cualquier otro cambio se hará efectivo en la fecha en la que lo publiquemos. Si
                                utilizas los <b>Servicios</b>
                                después de la fecha efectiva de cualquier cambio, dicha utilización constituirá la
                                aceptación de los
                                términos y las <b>Condiciones</b> que se revisaron.
                            </p>
                            <p class="ident-50">
                                <u><b>Renuncia.-</b></u> Ninguna falta u omisión de alguna de las partes al ejercer un
                                derecho bajo las
                                <b>Condiciones</b>, incluida la <b>Política de usos aceptables</b>, constituirá una
                                renuncia a dicho
                                derecho. Ninguna
                                renuncia bajo las <b>Condiciones</b> será efectiva salvo que se haga por escrito y esté
                                firmada por y
                                con las
                                formalidades que la ley exija, para que se considere aceptada la renuncia.
                            </p>
                            <p class="ident-50">
                                <u><b>Nulidad parcial.-</b></u> Las <b>Condiciones</b>, incluida la <b>Política de usos
                                aceptables</b>,
                                se establecen
                                bajo los principios de la legislación aplicable del territorio. Sin embargo, si en algún
                                momento algún
                                tribunal competente determinara que alguna cláusula de las <b>Condiciones</b> es
                                contraria a la ley, se
                                entenderá modificada la cláusula bajo la perspectiva del tribunal, y se incluirá en las
                                mismas de manera
                                que cumpla con los objetivos de la cláusula original y las cláusulas restantes de las
                                <b>Condiciones</b>.
                            </p>
                            <p class="ident-50">
                                <u><b>Cesión.-</b></u> No puedes ceder ninguno de tus derechos ni delegar tus
                                obligaciones bajo estas
                                <b>Condiciones</b>, incluida la <b>Política de usos aceptables</b>, ni por aplicación de
                                la ley ni de
                                otra manera, sin
                                nuestro consentimiento previo por escrito (que no se negará sin razón). Sin embargo,
                                nosotros podremos
                                en cualquier momento ceder estas <b>Condiciones</b> en su totalidad (incluidos todos los
                                términos y las
                                condiciones que se incorporaron aquí para referencia) sin la necesidad de obtener tu
                                consentimiento, a
                                favor de cualquier tercero con quien se establezca una relación juridica corporativa.
                            </p>
                            <p class="ident-50">
                                <u><b>Legislación aplicable.-</b></u> Las <b>Condiciones</b>, incluida la <b>Política de
                                usos
                                aceptables</b>, y toda
                                disputa que surja de ellas o que esté relacionada con ellas, se regirá exclusivamente
                                por la misma
                                legislación aplicable del <b>Contrato</b>, así como los tribunales situados en la
                                jurisdicción
                                correspondiente
                                del <b>Contrato</b>, tendrán competencia exclusiva para juzgar toda disputa que surja o
                                que se relacione
                                con
                                estas <b>Condiciones</b>, incluida la <b>Política de usos aceptables</b>, o su
                                formación, interpretación
                                o
                                cumplimiento. Desde ahora, las partes consienten y se someten a la jurisdicción
                                exclusiva de dichos
                                tribunales, renunciando a cualquier otra jurisdicción o fuero presente o futuro. En toda
                                acción o
                                procedimiento para ejercer los derechos bajo las <b>Condiciones</b>, la parte que
                                prevalezca podrá
                                recuperar
                                los gastos razonables y los honorarios de los abogados en que se haya incurrido.
                            </p>
                            <p class="ident-50">
                                <u><b>Acuerdo completo.-</b></u> Las <b>Condiciones</b>, incluido cualquier término
                                incorporado por
                                referencia
                                a las mismas, constituyen el acuerdo completo entre tú y nosotros, y reemplazan
                                cualquier otro convenio,
                                propuesta o representación previa y contemporánea, oral o escrita, referente al tema. En
                                caso de
                                conflicto o inconsistencia entre las cláusulas de estas <b>Condiciones del usuario</b> y
                                las páginas
                                referenciadas en ellos, los términos de las <b>Condiciones</b> prevalecerán; sin
                                embargo, si se da un
                                conflicto
                                o una inconsistencia entre el <b>Contrato</b> y las <b>Condiciones</b> del usuario, los
                                términos del <b>Contrato</b>
                                prevalecerán, seguidos de las cláusulas de estas <b>Condiciones</b>, y a continuación
                                las páginas
                                referenciadas
                                en éstas últimas (como la <b>Política de usos aceptables</b> y la <b>Política de
                                privacidad</b>). El <b>Cliente</b>
                                se
                                responsabilizará de notificar a los <b>Usuarios autorizados</b> de tales conflictos o
                                inconsistencias, y
                                hasta
                                ese momento los términos aquí establecidos serán vinculantes.
                            </p>
                            <p class="font-18"><b>Contacta con Apithy.</b></p>
                            <p class="ident-50">
                                No dudes en contactar con nosotros si tienes alguna pregunta sobre las Condiciones de
                                servicio al
                                usuario de Apithy. Puedes escribirnos a <a
                                    href="mailto:info@apithy.com">info@apithy.com</a>. 
                            </p>
                            <p class="font-12">
                                <b>Apithy</b>. Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de México,
                                <b>Todos los
                                    derechos
                                    reservados.</b>
                            </p>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3><u><b>Política de usos adecuados</b></u></h3>
                            <p>Fecha efectiva: 1 de noviembre de 2018</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-justify">
                            <p class="ident-50">
                                Esta <b>Política de usos adecuados</b> establece una serie de conductas aceptables e
                                inaceptables para o
                                relacionadas con nuestros <b>Servicios</b>. Si consideramos que la violación de la
                                política es
                                deliberada,
                                reincidente o presenta un riesgo de daño inminente para otros <b>Usuarios</b>, nuestros
                                <b>Clientes</b>,
                                los <b>Servicios</b>
                                o cualquier tercero, podemos suspender o eliminar tu acceso. Esta política podría
                                cambiar a medida que
                                <b>Apithy</b> crezca y evolucione, así que por favor revisa las actualizaciones y
                                cambios con
                                regularidad. Los
                                términos en mayúsculas que se utilizan a continuación, pero que no están definidos en la
                                presente
                                política, tienen el significado que se establece en las <b>Condiciones de servicio del
                                usuario</b>.
                            </p>
                            <p><u><b>Conductas aceptables que deberás asumir como Usuario:</b></u></p>
                            <div class="">
                                <ul>
                                    <li>Cumplir con las Condiciones de servicio al usuario, incluyendo los términos de
                                        esta Política de
                                        usos
                                        adecuados.
                                    </li>
                                    <li>Cumplir con todas las leyes aplicables y los reglamentos gubernamentales,
                                        incluyendo pero sin
                                        limitarse a toda propiedad intelectual, datos, privacidad, etc.
                                    </li>
                                    <li>Transferir y difundir solo los datos de que el cliente así permita y que solo
                                        son consistentes
                                        con
                                        las leyes aplicables.
                                    </li>
                                    <li>Llevar a cabo todas las medidas necesarias razonablemente a tu alcance para
                                        evitar el acceso no
                                        autorizado o el uso de de los Servicios.
                                    </li>
                                    <li>Mantener con carácter de confidencial las contraseñas y toda información de
                                        inicio de sesión.
                                    </li>
                                    <li>Supervisar y controlar toda la actividad realizada a través de tu cuenta en
                                        relación con los
                                        Servicios.
                                    </li>
                                    <li>Notificarnos de forma oportuna si conoces o sospechas razonablemente sobre
                                        cualquier actividad
                                        ilegal o no autorizada, o sobre la existencia de una brecha de seguridad en tus
                                        cuentas o
                                        equipos,
                                        incluyendo pérdida, robo, divulgación no autorizada o uso de un nombre de
                                        usuario, contraseña o
                                        cuenta.
                                    </li>
                                    <li>Cumplir con todos los aspectos de las condiciones del usuario</li>
                                </ul>
                            </div>
                            <p><u><b>Conductas inaceptables que </b></u><u><b class="text-red">NO deberás asumir como
                                Usuario</b><b>:</b></u></p>
                            <div class="">
                                <ul>
                                    <li>Permitir que cualquier tercero, que no sea un Usuario autorizado, acceda o use
                                        un nombre de
                                        usuario o contraseña para los Servicios.
                                    </li>
                                    <li>Compartir, transferir o proporcionar acceso a una cuenta que ha sido
                                        exclusivamente designada
                                        para ti u otra persona.
                                    </li>
                                    <li>Utilizar los Servicios para almacenar y/o transmitir Datos del cliente con la
                                        intención de
                                        infringir o apropiarse de forma ilícita de contenido de propiedad intelectual,
                                        incluyendo pero
                                        no limitando la(s) marca(s) registrada(s) y los derechos de autor, o bien
                                        cualquier propiedad
                                        intelectual de terceros; o bien utilizar los servicios de manera dolosa o
                                        ilegal.
                                    </li>
                                    <li>Transferir o transmitir desde los Servicios datos, archivos, software o enlaces
                                        que contenga o
                                        redirijan a un virus, troyano, gusano u otro componente perjudicial, incluyendo
                                        la utilización
                                        de cualquier tecnología que pretenda acceder ilegalmente o descargue contenido o
                                        información
                                        almacenados dentro de los Servicios o en el hardware de Apithy o de algún
                                        tercero.
                                    </li>
                                    <li>Intentar realizar ingeniería inversa, descompilar, hackear, deshabilitar,
                                        interferir, desmontar,
                                        modificar, copiar, traducir o interrumpir las funciones, funcionalidad,
                                        integridad o rendimiento
                                        de los Servicios (incluido todo mecanismo utilizado para restringir o controlar
                                        la funcionalidad
                                        de los Servicios), politica-cookies.htmlcualquier uso de los Servicios por parte
                                        de terceros, o
                                        cualquier dato de terceros contenido en el presente (excepto en la medida en que
                                        tales
                                        restricciones estén prohibidas según la ley aplicable).
                                    </li>
                                    <li>Intentar obtener acceso no autorizado a los Servicios o sistemas o redes
                                        relacionados, o
                                        frustrar, evitar, desvíar, eliminar, desactivar o eludir cualquier protección de
                                        software o
                                        mecanismo de control de los Servicios.
                                    </li>
                                    <li>Acceder a los Servicios con el fin de crear un producto o servicio similar o
                                        competitivo, o bien
                                        copiar ideas, características, funciones o gráficos de los Servicios.
                                    </li>
                                    <li>Usar los Servicios de cualquier manera que cause daños a menores de edad o que
                                        interactúe o se
                                        dirija a personas menores de trece años.
                                    </li>
                                    <li>Suplantar a cualquier persona o entidad, incluyendo pero no limitando a uno de
                                        nuestros
                                        empleados, un "Administrador", un "Propietario" o cualquier Usuario autorizado;
                                        tampoco deberás
                                        manifestar falsamente ni tergiversa tu afiliación con una persona, organización
                                        o entidad.
                                    </li>
                                    <li>Utilizar los Servicios para proporcionar apoyo o recursos materiales (u ocultar
                                        o disfrazar la
                                        naturaleza, ubicación, fuente o propiedad de apoyo o recursos materiales) a
                                        cualquier
                                        organización que tenga el mismo giro o actividad de Apithy.
                                    </li>
                                    <li>Acceder, o buscar crear cuentas para los Servicios por un medio que no sea
                                        nuestras interfaces
                                        con soporte público (por ejemplo, "scraping" o creación de cuentas múltiples).
                                    </li>
                                    <li>Envíar comunicaciones no solicitadas, promociones, publicidad o correo basura.
                                    </li>
                                    <li>Colocar publicidad con un cliente de Apithy.</li>
                                    <li>Envíar información de identificación de fuente(s) alterada(s), engañosa(s) o
                                        falsa(s), lo que
                                        incluye "spoofing" o "phishing".
                                    </li>
                                    <li>Abusar de las referencias o promociones para obtener más créditos que los
                                        merecidos.
                                    </li>
                                    <li>Sublicenciar, revender, compartir o explotar los Servicios de manera similar.
                                    </li>
                                    <li>Acceder o utilizar los Servicios en nombre o en beneficio de cualquier entidad
                                        de aserción de
                                        patentes.
                                    </li>
                                    <li>Utilizar el contacto u otra información de usuario obtenida de los Servicios (lo
                                        que incluye las
                                        direcciones de correo electrónico) para contactar a Usuarios autorizados que no
                                        se encuentren
                                        dentro de los Servicios sin su autorización expresa ni autoridad; tampoco, crear
                                        o distribuir
                                        listas de direcciones u otras recopilaciones de información de contacto o de
                                        perfil de usuario
                                        para Usuarios autorizados para su uso fuera de los Servicios.
                                    </li>
                                    <li>Autorizar, permitir, habilitar, inducir o promover que cualquier tercero realice
                                        lo antes
                                        mencionado.
                                    </li>
                                    <li>En general, realizar conductas o actividades contrarias a las establecidas por
                                        la ley.
                                    </li>
                                </ul>
                            </div>
                            <p>
                                No dudes en contactar con nosotros si tienes alguna pregunta sobre la <b>Política de
                                usos adecuados</b> de
                                <b>Apithy</b> a través del correo electrónico <a href="mailto:info@apithy.com">info@apithy.com</a>.
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="font-12">
                                <b>Apithy</b>. Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de México,
                                <b>Todos los derechos reservados.</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: none;" id="privacy">
                <div class="container legal">
                    <div class="row">
                        <div class="col-12 text-center mt-3">
                            <h2>Apithy: PLATAFORMA DE SERVICIOS DE GESTIÓN DE APRENDIZAJE</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <h4>Aviso de privacidad</h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 text-justify">
                            <p class="ident-50">
                                Tu privacidad y confianza son muy importantes para nosotros. Por ello, y en cumplimiento
                                con lo
                                establecido por la Ley Federal de Protección de Datos Personales en Posesión de
                                Particulares,
                                <b>Geekstadium, S. A. P. I. de C. V.</b> (en lo sucesivo <b>“Apithy”</b>) hace de tu
                                conocimiento la
                                normativa
                                de privacidad y uso de datos personales que rige en su organización, misma que en todo
                                momento impone
                                que el tratamiento de los mismos sea legítimo, seguro, controlado e informado, a efecto
                                de garantizar la
                                privacidad de los mismos, cumpliendo para ello con los principios rectores de protección
                                de datos
                                personales.
                            </p>
                            <p class="ident-50">
                                En consecuencia, <b>Apithy</b> tiene la calidad de <i><b>Responsable</b></i> de tus
                                datos personales
                                (según se define este
                                concepto en la citada ley), quien tiene su domicilio legal en Mayorazgo de Orduña No.
                                35, Colonia Xoco,
                                C. P. 03330, Ciudad de México, por lo que los datos personales que actualmente o en el
                                futuro obren en
                                nuestras bases de datos, y que proporciones a <b>Apithy</b>, serán tratados y utilizados
                                única y
                                exclusivamente
                                por ella, y por aquellos terceros que, por la naturaleza de sus trabajos o funciones
                                tengan la necesidad
                                de tratar y/o utilizar para los fines que en forma enunciativa pero no limitativa se
                                describen a
                                continuación: para identificarte, ubicarte, comunicarte, contactarte, enviarte
                                información, etc.; por
                                cualquier medio, incluyendo el electrónico y/o a través de la red pública mundial
                                conocida como
                                internet. <b>Apithy</b> podrá transferir los datos personales que obren en sus bases de
                                datos única y
                                exclusivamente a terceros que tengan la calidad de instructores o socios comerciales,
                                salvo que tú, en
                                calidad de <i><b>Titular</b></i>, manifiestes expresamente tu oposición, en términos de
                                los dispuesto
                                por la Ley
                                Federal de Protección de Datos Personales en Posesión de los Particulares. En todo
                                momento, el uso de
                                los datos personales tendrá relación con el tipo de interacción jurídica que tienes con
                                <b>Apithy</b>:
                                ya sea
                                comercial, civil, mercantil o de cualquier otra naturaleza.
                            </p>
                            <p class="ident-100">
                                La temporalidad del manejo de los datos personales será indefinida, a partir de la fecha
                                en que tú nos
                                la proporcionaste.
                            </p>
                            <p class="ident-50">
                                Podrás ejercer los derechos de acceso, rectificación, cancelación u oposición (Derechos
                                A. R. C. O.) que
                                le confiere la Ley, en cualquier momento, dirigiendo tu solicitud en atención al área de
                                Privacidad
                                mediante: (i) correo electrónico a la dirección <a href="mailto:privacidad@apithy.com">privacidad@apithy.com</a>.,
                                o bien; (ii) en el siguiente
                                domicilio: Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de México, en
                                días hábiles de
                                las 9:00 a las 16:00 horas. Para lo anterior, deberás hacernos saber fehacientemente
                                mediante documento
                                escrito físico y/o electrónico según corresponda, los datos personales que deseas sean
                                rectificados,
                                cancelados o revisados, así como el propósito para el cual los aportaste, cumpliendo con
                                los siguientes
                                requisitos:
                            </p>
                            <ol type="a">
                                <li>
                                    <p>
                                        Incluir tu nombre y firma autógrafa como <i><b>Titular</b></i>, así como un
                                        domicilio y/o
                                        dirección de correo electrónico para comunicarte la respuesta a tu solicitud.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Acompañar los documentos oficiales que acrediten tu identidad.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Incluir una descripción clara y precisa de los datos personales respecto de los
                                        cuales ejercitas
                                        los derechos que la ley te confiere.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Incluir cualquier elemento o documento que facilite la localización de los datos
                                        personales de
                                        que se traten.
                                    </p>
                                </li>
                            </ol>
                            <p class="ident-50">
                                <i><b>Apithy</b></i> se reserva el derecho de cambiar, modificar, complementar, alterar
                                o ampliar el
                                presente aviso de
                                privacidad en cualquier tiempo, y lo mantendrá siempre a disposición a través de los
                                medios que
                                establece la legislación en la materia.
                            </p>
                        </div>
                        <div class="col-12">
                            <p class="font-12">
                                <i><b>Apithy</b></i>. Mayorazgo de Orduña No. 35, Colonia Xoco, C. P. 03330, Ciudad de
                                México.
                                <i><b>Todos los derechos reservados.</b></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" style="display: none;" id="service_agreement">
                <div class="container legal">
                    <div class="row">
                        <div class="col-12 text-center mt-3">
                            <h2>
                                ACUERDO DE SERVICIO
                            </h2>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 text-justify">
                            <p class="ident-50">
                                Los siguientes términos de servicio ("<b>Términos</b>" o "<b>Acuerdo</b>") constituyen
                                un acuerdo
                                vinculante entre el Proveedor de contenido, ya sea como persona física, o como
                                representante legal de una entidad, compañía o empresa ("<b>Proveedor de contenido</b>"
                                o "<b>tú</b>")
                                y Geekstadium, S. A. P. I. de C. V. ("<b>Apithy</b>"), los cuales rigen el uso de los
                                servicios. Se puede acceder a ellos: (a) como un servicio gratuito para el usuario que
                                tiene las especificaciones descritas en el Plan respectivo (la "<b>Versión gratuita</b>"),
                                y;
                                (b) como un servicio pagado que tiene las especificaciones descritas en el Plan
                                Tarifario, para el cual el <b>Proveedor de contenido</b> paga una tarifa de suscripción
                                mensual
                                o anual (la "<b>Versión de pago</b>"). El uso de los Servicios por parte del Proveedor
                                de
                                contenido está sujeto a: (a) los términos y condiciones establecidos a continuación, y;
                                (b) la política de privacidad de Apithy.
                            </p>
                            <p class="ident-50">
                                Los Servicios están disponibles solo para personas mayores de 18 años. Si el Proveedor
                                de contenido es una persona física, el Proveedor de contenido declara y garantiza que
                                tiene al menos 18 años de edad. 
                            </p>
                            <p class="ident-50">
                                El Proveedor de contenido reconoce que la Versión gratuita se proporciona sin cargo y,
                                por lo tanto, los términos que rigen el uso de dicha versión son diferentes de los
                                términos que rigen el uso de la Versión de pago. 
                            </p>
                            <p class="ident-50">
                                Al utilizar los Servicios en cualquiera de las modalidades, aceptas los términos aquí
                                especificados, ya sea como persona física, o bien si estás accediendo a los servicios en
                                nombre de tu empleador o de otra entidad que representas, por lo que garantizas que
                                tienes las facultades legales debidas para aceptar estos términos en representación. Si
                                no cuentas con tal facultad, o si no estás de acuerdo con estos términos y condiciones,
                                no puedes utilizar los servicios.
                            </p>
                            <ol>
                                <li>
                                    <p class="font-18">DEFINICIONES:</p>
                                    <ol type="a">
                                        <li>
                                            "Información confidencial" significa todos los secretos comerciales,
                                            conocimientos
                                            técnicos, invenciones, desarrollos, software y otra información financiera,
                                            comercial o técnica divulgada por o para una parte en relación con este
                                            Acuerdo,
                                            pero sin incluir ninguna información que la parte receptora pueda demostrar
                                            que: (
                                            a) ya la conoce legítimamente y sin restricciones, (b) le ha sido otorgada
                                            legítimamente por un tercero sin restricciones y sin incumplimiento de
                                            ninguna
                                            obligación para con la parte reveladora, (c) generalmente está disponible al
                                            público
                                            sin incumplimiento de este Acuerdo o (d ) ha sido desarrollada de forma
                                            independiente por él sin depender de la Información Confidencial de la parte
                                            reveladora. Toda la información de precios es información confidencial de
                                            Apithy.
                                        </li>
                                        <li>
                                            “Contenido digital” significa todo texto, software, guiones, gráficos,
                                            fotos,
                                            sonidos, música, videos, combinaciones audiovisuales, funciones interactivas
                                            y otros
                                            materiales que se pueden ver, acceder o contribuir a los Servicios.
                                        </li>
                                        <li>
                                            "Contenido del Proveedor de contenido" significa Contenido contribuido a los
                                            Servicios por el Proveedor de contenido.
                                        </li>
                                        <li>
                                            "Datos del proveedor de contenido" hace referencia a toda la información de
                                            registro
                                            del Proveedor de contenido y otros datos de transacción recopilados,
                                            procesados ​​y
                                            retenidos por Apithy en relación con la prestación de los Servicios.
                                        </li>
                                        <li>
                                            "Plan" se refiere a los planes gratuitos o de pago de Apithy, según
                                            corresponda y
                                            como se describen en los planes tarifarios.
                                        </li>
                                        <li>
                                            "Servicios" significa los servicios alojados por Apithy y proporcionados al
                                            Proveedor de contenido en virtud de este Acuerdo.
                                        </li>
                                        <li>
                                            “Sistemas” significa módems, servidores, software, equipos de redes y
                                            comunicaciones
                                            y servicios auxiliares que son propiedad, controlados o adquiridos por el
                                            Proveedor
                                            de contenido.
                                        </li>
                                        <li>
                                            "Actualizaciones" significa cualquier parche, revisión o actualización de
                                            los
                                            Servicios entregados por Apithy.
                                        </li>
                                    </ol>
                                </li>
                                <br>
                                <li>
                                    SERVICIOS. Sujeto a todos los términos y condiciones de este Acuerdo, Apithy
                                    proporciona los servicios de alojamiento de contenido digital, ya sea a través de la
                                    plataforma, o bien, utilizando proveedores de servicios de terceros.
                                </li>
                                <br>
                                <li>
                                    MEDIDAS DE SEGURIDAD. El Proveedor de contenido puede acceder a los Servicios según
                                    lo indique Apithy a través de una combinación de uno o más nombres de usuario y
                                    contraseñas.
                                </li>
                                <br>
                                <li>
                                    El Proveedor de contenido asumirá toda la responsabilidad de la seguridad de cada
                                    uno de sus nombres de usuario y contraseñas, y será el único responsable del uso de
                                    los Servicios a través de dichos nombres de usuario o contraseñas. El Proveedor de
                                    contenido acepta notificar de inmediato a Apithy sobre cualquier uso no autorizado
                                    de los Servicios o cualquier otra violación de seguridad conocida por el Proveedor
                                    de contenido.
                                </li>
                                <br>
                                <li>
                                    <p>
                                        USOS NO PERMITIDOS CON LA CONTRATACIÓN DEL SERVICIO. Como condición para el uso
                                        de los Servicios, el Proveedor de contenido se obliga a no usar los Servicios
                                        para cualquier propósito que esté prohibido por estos Términos.
                                    </p>
                                    <p>
                                        A modo de ejemplo, y no como limitación, no debes cargar, enviar, distribuir,
                                        facilitar ninguno de los anteriores, ni utilizar los Servicios de una manera
                                        que:
                                    </p>
                                    <ol type="a">
                                        <li>
                                            infringa o viole los derechos de propiedad intelectual o cualquier otro
                                            derecho de cualquier otra persona o entidad (incluida Apithy);
                                        </li>
                                        <li>
                                            viole cualquier ley o reglamento;
                                        </li>
                                        <li>
                                            sea dañino, fraudulento, engañoso, amenazante, acosador, difamatorio,
                                            obsceno, pornográfico, contiene o muestra desnudos, o de cualquier otra
                                            forma objetable, según lo determine Apithy a su entera discreción;
                                        </li>
                                        <li>
                                            ponga en peligro la seguridad de su cuenta de Apithy o la de cualquier otra
                                            persona (como permitir que otra persona inicie sesión en los Servicios como
                                            usted)
                                        </li>
                                        <li>
                                            intente, de cualquier manera, obtener la contraseña, cuenta u otra
                                            información de seguridad de cualquier otro usuario;
                                        </li>
                                        <li>
                                            viole la seguridad de cualquier red de computadoras, o descifra contraseñas
                                            o códigos de encriptación de seguridad;
                                        </li>
                                        <li>
                                            copie o almacene cualquier porción significativa del Contenido;
                                        </li>
                                        <li>
                                            descompile, haga ingeniería inversa, o intente obtener el código fuente o
                                            ideas subyacentes o información de, o relacionada con los Servicios.
                                        </li>
                                    </ol>
                                    <p>
                                        Además, el Proveedor de contenido no deberá (directa o indirectamente), o
                                        permitir que terceros: (a) utilicen cualquier Información Confidencial de Apithy
                                        para crear cualquier software, documentación o servicios que sean similares o
                                        cualquier documentación provista en conexión con ellos; (b) modificar, traducir
                                        o crear obras derivadas de cualquier parte de los Servicios, (c) copiar,
                                        licenciar, sublicenciar, vender, revender, gravar, alquilar, arrendar,
                                        compartir, distribuir, transferir o de otra manera usar o explotar o poner a
                                        disposición los Servicios en beneficio de terceros sin el consentimiento previo
                                        por escrito de Apithy y/o de su titular. Deberás cumplir con todas las leyes y
                                        regulaciones locales, estatales, nacionales e internacionales aplicables.
                                        Finalmente, debes ser un humano. No se permite el acceso a los Servicios por
                                        "bots" u otros métodos automatizados.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    CAMBIOS EN LOS SERVICIOS. Apithy se reserva el derecho de modificar o interrumpir
                                    cualquier Servicio o Plan (en su totalidad o en parte) en cualquier momento.
                                </li>
                                <br>
                                <li>
                                    CAMBIOS EN LOS TÉRMINOS. Nos reservamos el derecho de cambiar los Términos en
                                    cualquier momento, pero si lo hacemos, te lo comunicaremos mediante un aviso en el
                                    sitio web de la plataforma, o bien, enviándote un correo electrónico. Si no está de
                                    acuerdo con los nuevos Términos, puede rechazarlos; desafortunadamente, eso
                                    significa que ya no podrás utilizar los Servicios. Sin embargo, si utilizas los
                                    Servicios de alguna manera después de que un cambio en los Términos sea efectivo,
                                    significa que acepta todos los cambios. 
                                </li>
                                <br>
                                <li>
                                    LIMITACIONES. Apithy no será responsable por cualquier falla en los Servicios que
                                    resulte de o sea atribuible a: (a) los Sistemas del Proveedor de contenido, (b)
                                    fallas en los servicios de telecomunicaciones u otros servicios o equipos fuera de
                                    las instalaciones de Apithy, (c) los productos del Proveedor de contenido o de
                                    terceros, servicios, negligencia, actos u omisiones, (d) cualquier fuerza mayor o
                                    causa más allá del control razonable de Apithy, (e) mantenimiento programado, o;(f)
                                    acceso no autorizado, violación de cortafuegos u otro pirateo por parte de terceros.
                                </li>
                                <br>
                                <li>
                                    SISTEMAS. El Proveedor de contenido deberá obtener y operar todos los Sistemas
                                    necesarios para conectarse, acceder o utilizar los Servicios, y proporcionar todos
                                    los servicios de respaldo, recuperación y mantenimiento correspondientes. El
                                    proveedor de contenido se asegurará de que todos los sistemas sean compatibles con
                                    los servicios. El proveedor de contenido deberá mantener la integridad y seguridad
                                    de sus sistemas (físicos, electrónicos y de otro tipo).
                                </li>
                                <br>
                                <li>
                                    DERECHOS DE PROPIEDAD INTELECTUAL. Excepto por el Contenido del Proveedor de
                                    contenido, Apithy (y sus otorgantes de licencias) posee todos los derechos y títulos
                                    inherentes a los Servicios y todas las modificaciones, mejoras y actualizaciones a
                                    los mismos. Apithy se reserva todos los derechos no otorgados expresamente en este
                                    documento. 
                                </li>
                                <br>
                                <li>
                                    <p>TITULARIDAD DEL CONTENIDO Y LICENCIA.</p>
                                    <p>
                                        El Proveedor de contenido es el único titular de todos los derechos de propiedad
                                        intelectual inherentes a su contenido. Sin embargo, por el simple hecho de
                                        contratar los servicios, otorga a Apithy y sus afiliados una licencia limitada,
                                        mundial y no exclusiva para copiar, transmitir, distribuir, realizar
                                        públicamente y mostrar (a través de todos los medios ahora conocidos o creados
                                        posteriormente), y/o realizar trabajos derivados de su contenido, con el
                                        propósito de: (i) mostrar el contenido dentro del Servicio de Apithy; (ii)
                                        mostrar el contenido en sitios web y aplicaciones de terceros a través de una
                                        incrustación del mismo sujeto a sus opciones de privacidad; (iii) permitir que
                                        otros usuarios reproduzcan, pero sujeto a sus opciones de privacidad, el
                                        contenido; (iv) promover el Servicio de Apithy, siempre que haya puesto el
                                        contenido a disposición del público, y; (v) archivar o conservar el contenido
                                        para disputas, procedimientos legales o investigaciones.
                                    </p>
                                    <p>
                                        Por lo anterior, desde el momento de la contratación, el Proveedor de contenido
                                        garantiza que es el único titular de todos los derechos relacionados con el
                                        contenido respecto del cual solicitas la contratación de los servicios.
                                    </p>
                                    <p>
                                        En caso de cualquier reclamo por parte de terceros relacionado con el contenido,
                                        ya sea de forma total o parcial, Apithy podra reclamar al Proveedor de contenido
                                        los daños y perjuicios que le ocasione.
                                    </p>
                                    <p>
                                        La licencia que el Proveedor de contenido otorga a Apithy respecto de los
                                        contenidos, estará vigente por un periodo de tiempo de 5 años, contados estos a
                                        partir de que el contenido es distribuido y divulgado en la plataforma, ya sea
                                        en su versión original, o bien mediante el trabajo derivado que se realizó para
                                        la inclusión del mismo en la plataforma.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    INFORMACIÓN CONFIDENCIAL. Las Partes convienen que, no utilizarán ni divulgarán
                                    ninguna información que esté catalogada como confidencial y que pertenezca a la otra
                                    parte, a menos que se conceda el respectivo consentimiento para ell; en este caso,
                                    se deberá usar el cuidado razonable para proteger la Información Confidencial de la
                                    parte que corresponda.
                                </li>
                                <br>
                                <li>
                                    <p>
                                        CONTRAPRESTACIÓN. El Proveedor de contenido acepta pagar a Apithy las tarifas
                                        y/o importes especificados en el Plan seleccionado.
                                    </p>
                                    <p>
                                        Para ello, el Proveedor de contenido debe proporcionar a Apithy la información
                                        de facturación correcta y completa, incluido el nombre legal, la dirección, el
                                        número de teléfono y una tarjeta de crédito válida. La tarjeta del proveedor de
                                        contenido nunca será cargada sin su autorización. Al enviar dicha información de
                                        la tarjeta de crédito, el Proveedor de contenido otorga a Apithy permiso para
                                        realizar todos los cargos incurridos a través de su cuenta a la tarjeta de
                                        crédito designada. 
                                    </p>
                                    <p>
                                        Los Servicios se facturan por adelantado en forma mensual o anual, según el plan
                                        de pago elegido por el Proveedor de contenido. Apithy no proporcionará
                                        reembolsos o créditos en el caso de cancelaciones, rebajas o cuando haya partes
                                        no utilizadas de los Servicios en una cuenta abierta. Todos los futuros cargos
                                        recurrentes por los Servicios seguirán el ciclo de facturación mensual o anual
                                        (según lo elija el Proveedor de contenido).
                                    </p>
                                    <p>
                                        En caso de falta de pago, los servicios serán rescindidos de manera inmediata.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    LÍMITE DE RESPONSABILIDAD. Con excepción de lo especificado aquí, los servicios se
                                    proporcionan "<i>tal cual</i>" sin garantía de ningún tipo. Apithy no garantiza que
                                    los
                                    servicios cumplen los requisitos del proveedor de contenido o que su operación sea
                                    ininterrumpida o libre de errores.
                                </li>
                                <br>
                                <li>
                                    <p>
                                        VIGENCIA. Este Acuerdo comenzará a partir de la fecha en que sea aceptado. Con
                                        respecto a un usuario de la Versión de pago, este Acuerdo continuará en vigor
                                        durante el plazo inicial especificado en el Plan (o si no se especifica dicho
                                        término, durante el ciclo de facturación), y se renovará de manera automática
                                        con base en el plan seleccionado. Sin embargo, cualquiera de las partes puede
                                        optar por no renovar este Acuerdo mediante notificación por escrito de dicha
                                        elección, con por lo menos un mes antes en que ocurra dihca renovación. 
                                    </p>
                                    <p>
                                        En el caso del Proveedor de contenido, éste será el único responsable de
                                        notificar correctamente a Apithy sobre su elección de no renovar
                                        automáticamente, siguiendo las instrucciones de cancelación disponibles en la
                                        cuenta de Apithy del Proveedor de contenido. Con respecto a un usuario de la
                                        Versión gratuita, este Acuerdo continuará vigente hasta que cualquiera de las
                                        partes finalice este Acuerdo con al menos 5 días hábiles de notificación por
                                        escrito a la otra parte.
                                    </p>
                                    <p>
                                        Igualmente, el Proveedor de contenido acepta desde ahora que Apithy tendrá la
                                        facultad para retirar de la plataforma el contenido que le fue proveído, en caso
                                        de que él mismo no tenga la recurrencia necesaria de explotación (según el
                                        criterio de Apithy), o bien cuando el tema que aborda el propio contenido no es
                                        de actualidad.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    <p>
                                        LINEAMIENTOS EN MATERIA DE DERECHOS DE AUTOR (Copyright). Es política de Apithy,
                                        en materia de derechos de autor: (a) bloquear el acceso o eliminar Contenido que
                                        cree de buena fe, es material protegido por derechos de autor y que ha sido
                                        copiado y distribuido ilegalmente por cualquiera de sus afiliados, usuarios y/o
                                        proveedores de contenido, y; (b) eliminar e interrumpir el servicio a
                                        reincidentes.
                                    </p>
                                    <p>
                                        Si cualquier tercero considera que el Contenido que reside o es accesible a
                                        través de la plataforma infringe un derecho de autor, podrá enviar un <i>aviso
                                        de
                                        infracción de copyright</i> que contenga la siguiente información:
                                    </p>
                                    <ol type="a">
                                        <li>
                                            Una firma física o electrónica de una persona autorizada para actuar en
                                            nombre del propietario de los derechos de autor que presuntamente se ha
                                            infringido;
                                        </li>
                                        <li>
                                            Identificación de obras o materiales objeto de infracción;
                                        </li>
                                        <li>
                                            Identificación del Contenido que se alega que infringe, incluida la
                                            información sobre la ubicación del Contenido que el propietario de los
                                            derechos de autor desea eliminar, con detalles suficientes para que Apithy
                                            sea capaz de encontrar y verificar su existencia;
                                        </li>
                                        <li>
                                            Información de contacto sobre el notificador, incluida la dirección, el
                                            número de teléfono y, si está disponible, la dirección de correo
                                            electrónico;
                                        </li>
                                        <li>
                                            Una declaración de que el notificador cree de buena fe que el Contenido no
                                            está autorizado por el propietario de los derechos de autor, su agente o la
                                            ley, y;
                                        </li>
                                        <li>
                                            Una declaración bajo protesta de decir verdad, de que la información
                                            proporcionada es correcta y la parte notificante está autorizada para
                                            presentar la queja en nombre del propietario de los derechos de autor.
                                        </li>
                                    </ol>
                                    <p>
                                        Una vez que Apithy reciba dicha notificación de infracción, procederá conforme a
                                        lo siguiente:
                                    </p>
                                    <ol type="a">
                                        <li>
                                            Eliminará y deshabilitará el accesos al contenido del infractor;
                                        </li>
                                        <li>
                                            Notificará a la persona que proveyo dicho Contenido, y/o al usuario que
                                            recurrentemente lo usa.
                                        </li>
                                    </ol>
                                </li>
                                <br>
                                <li>
                                    ACUERDO TOTAL. Los términos y condciones aquí vertidos, junto con la política de
                                    privacidad de Apithy y los Planes tarifarios aplicables, constituyen el acuerdo
                                    total y reemplaza todas las negociaciones, entendimientos o acuerdos anteriores
                                    (orales o escritos) entre las partes sobre los servicios. En caso de conflicto o
                                    inconsistencia entre el Acuerdo y el Plan, los términos y condiciones de los Planes
                                    tarifarios prevalecerán y serán dominantes.
                                </li>
                                <br>
                                <li>
                                    JURISDICCIÓN. Este Acuerdo se regirá e interpretará de acuerdo con las leyes de los
                                    Estados Unidos Mexicanos; específicamente, con las leyes y tribunales de la Ciudad
                                    de México.
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="" style="display: none;" id="service_agreement_partner">
                <div class="container legal">
                    <div class="row">
                        <div class="col-12 text-center mt-3">
                            <h2>
                                ACUERDO DE SERVICIO
                            </h2>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 text-justify">
                            <p class="ident-50">
                                Los siguientes términos de servicio ("<b>Términos</b>" o "<b>Acuerdo</b>") constituyen
                                un acuerdo
                                vinculante entre el Proveedor de contenido, ya sea como persona física, o como
                                representante legal de una entidad, compañía o empresa ("<b>Proveedor de contenido</b>"
                                o "<b>tú</b>")
                                y Geekstadium, S. A. P. I. de C. V. ("<b>Apithy</b>"), los cuales rigen el uso de los
                                servicios. Se puede acceder a ellos: (a) como un servicio gratuito para el usuario que
                                tiene las especificaciones descritas en el Plan respectivo (la "<b>Versión gratuita</b>"),
                                y;
                                (b) como un servicio pagado que tiene las especificaciones descritas en el Plan
                                Tarifario, para el cual el <b>Proveedor de contenido</b> paga una tarifa de suscripción
                                mensual
                                o anual (la "<b>Versión de pago</b>"). El uso de los Servicios por parte del Proveedor
                                de
                                contenido está sujeto a: (a) los términos y condiciones establecidos a continuación, y;
                                (b) la política de privacidad de Apithy.
                            </p>
                            <p class="ident-50">
                                Los Servicios están disponibles solo para personas mayores de 18 años. Si el Proveedor
                                de contenido es una persona física, el Proveedor de contenido declara y garantiza que
                                tiene al menos 18 años de edad. 
                            </p>
                            <p class="ident-50">
                                El Proveedor de contenido reconoce que la Versión gratuita se proporciona sin cargo y,
                                por lo tanto, los términos que rigen el uso de dicha versión son diferentes de los
                                términos que rigen el uso de la Versión de pago. 
                            </p>
                            <p class="ident-50">
                                Al utilizar los Servicios en cualquiera de las modalidades, aceptas los términos aquí
                                especificados, ya sea como persona física, o bien si estás accediendo a los servicios en
                                nombre de tu empleador o de otra entidad que representas, por lo que garantizas que
                                tienes las facultades legales debidas para aceptar estos términos en representación. Si
                                no cuentas con tal facultad, o si no estás de acuerdo con estos términos y condiciones,
                                no puedes utilizar los servicios.
                            </p>
                            <ol>
                                <li>
                                    <p class="font-18">DEFINICIONES:</p>
                                    <ol type="a">
                                        <li>
                                            "Información confidencial" significa todos los secretos comerciales,
                                            conocimientos
                                            técnicos, invenciones, desarrollos, software y otra información financiera,
                                            comercial o técnica divulgada por o para una parte en relación con este
                                            Acuerdo,
                                            pero sin incluir ninguna información que la parte receptora pueda demostrar
                                            que: (
                                            a) ya la conoce legítimamente y sin restricciones, (b) le ha sido otorgada
                                            legítimamente por un tercero sin restricciones y sin incumplimiento de
                                            ninguna
                                            obligación para con la parte reveladora, (c) generalmente está disponible al
                                            público
                                            sin incumplimiento de este Acuerdo o (d ) ha sido desarrollada de forma
                                            independiente por él sin depender de la Información Confidencial de la parte
                                            reveladora. Toda la información de precios es información confidencial de
                                            Apithy.
                                        </li>
                                        <li>
                                            “Contenido digital” significa todo texto, software, guiones, gráficos,
                                            fotos,
                                            sonidos, música, videos, combinaciones audiovisuales, funciones interactivas
                                            y otros
                                            materiales que se pueden ver, acceder o contribuir a los Servicios.
                                        </li>
                                        <li>
                                            "Contenido del Proveedor de contenido" significa Contenido contribuido a los
                                            Servicios por el Proveedor de contenido.
                                        </li>
                                        <li>
                                            "Datos del proveedor de contenido" hace referencia a toda la información de
                                            registro
                                            del Proveedor de contenido y otros datos de transacción recopilados,
                                            procesados ​​y
                                            retenidos por Apithy en relación con la prestación de los Servicios.
                                        </li>
                                        <li>
                                            "Plan" se refiere a los planes gratuitos o de pago de Apithy, según
                                            corresponda y
                                            como se describen en los planes tarifarios.
                                        </li>
                                        <li>
                                            "Servicios" significa los servicios alojados por Apithy y proporcionados al
                                            Proveedor de contenido en virtud de este Acuerdo.
                                        </li>
                                        <li>
                                            “Sistemas” significa módems, servidores, software, equipos de redes y
                                            comunicaciones
                                            y servicios auxiliares que son propiedad, controlados o adquiridos por el
                                            Proveedor
                                            de contenido.
                                        </li>
                                        <li>
                                            "Actualizaciones" significa cualquier parche, revisión o actualización de
                                            los
                                            Servicios entregados por Apithy.
                                        </li>
                                    </ol>
                                </li>
                                <br>
                                <li>
                                    SERVICIOS. Sujeto a todos los términos y condiciones de este Acuerdo, Apithy
                                    proporciona los servicios de alojamiento de contenido digital, ya sea a través de la
                                    plataforma, o bien, utilizando proveedores de servicios de terceros.
                                </li>
                                <br>
                                <li>
                                    MEDIDAS DE SEGURIDAD. El Proveedor de contenido puede acceder a los Servicios según
                                    lo indique Apithy a través de una combinación de uno o más nombres de usuario y
                                    contraseñas.
                                </li>
                                <br>
                                <li>
                                    El Proveedor de contenido asumirá toda la responsabilidad de la seguridad de cada
                                    uno de sus nombres de usuario y contraseñas, y será el único responsable del uso de
                                    los Servicios a través de dichos nombres de usuario o contraseñas. El Proveedor de
                                    contenido acepta notificar de inmediato a Apithy sobre cualquier uso no autorizado
                                    de los Servicios o cualquier otra violación de seguridad conocida por el Proveedor
                                    de contenido.
                                </li>
                                <br>
                                <li>
                                    <p>
                                        USOS NO PERMITIDOS CON LA CONTRATACIÓN DEL SERVICIO. Como condición para el uso
                                        de los Servicios, el Proveedor de contenido se obliga a no usar los Servicios
                                        para cualquier propósito que esté prohibido por estos Términos.
                                    </p>
                                    <p>
                                        A modo de ejemplo, y no como limitación, no debes cargar, enviar, distribuir,
                                        facilitar ninguno de los anteriores, ni utilizar los Servicios de una manera
                                        que:
                                    </p>
                                    <ol type="a">
                                        <li>
                                            infringa o viole los derechos de propiedad intelectual o cualquier otro
                                            derecho de cualquier otra persona o entidad (incluida Apithy);
                                        </li>
                                        <li>
                                            viole cualquier ley o reglamento;
                                        </li>
                                        <li>
                                            sea dañino, fraudulento, engañoso, amenazante, acosador, difamatorio,
                                            obsceno, pornográfico, contiene o muestra desnudos, o de cualquier otra
                                            forma objetable, según lo determine Apithy a su entera discreción;
                                        </li>
                                        <li>
                                            ponga en peligro la seguridad de su cuenta de Apithy o la de cualquier otra
                                            persona (como permitir que otra persona inicie sesión en los Servicios como
                                            usted)
                                        </li>
                                        <li>
                                            intente, de cualquier manera, obtener la contraseña, cuenta u otra
                                            información de seguridad de cualquier otro usuario;
                                        </li>
                                        <li>
                                            viole la seguridad de cualquier red de computadoras, o descifra contraseñas
                                            o códigos de encriptación de seguridad;
                                        </li>
                                        <li>
                                            copie o almacene cualquier porción significativa del Contenido;
                                        </li>
                                        <li>
                                            descompile, haga ingeniería inversa, o intente obtener el código fuente o
                                            ideas subyacentes o información de, o relacionada con los Servicios.
                                        </li>
                                    </ol>
                                    <p>
                                        Además, el Proveedor de contenido no deberá (directa o indirectamente), o
                                        permitir que terceros: (a) utilicen cualquier Información Confidencial de Apithy
                                        para crear cualquier software, documentación o servicios que sean similares o
                                        cualquier documentación provista en conexión con ellos; (b) modificar, traducir
                                        o crear obras derivadas de cualquier parte de los Servicios, (c) copiar,
                                        licenciar, sublicenciar, vender, revender, gravar, alquilar, arrendar,
                                        compartir, distribuir, transferir o de otra manera usar o explotar o poner a
                                        disposición los Servicios en beneficio de terceros sin el consentimiento previo
                                        por escrito de Apithy y/o de su titular. Deberás cumplir con todas las leyes y
                                        regulaciones locales, estatales, nacionales e internacionales aplicables.
                                        Finalmente, debes ser un humano. No se permite el acceso a los Servicios por
                                        "bots" u otros métodos automatizados.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    CAMBIOS EN LOS SERVICIOS. Apithy se reserva el derecho de modificar o interrumpir
                                    cualquier Servicio o Plan (en su totalidad o en parte) en cualquier momento.
                                </li>
                                <br>
                                <li>
                                    CAMBIOS EN LOS TÉRMINOS. Nos reservamos el derecho de cambiar los Términos en
                                    cualquier momento, pero si lo hacemos, te lo comunicaremos mediante un aviso en el
                                    sitio web de la plataforma, o bien, enviándote un correo electrónico. Si no está de
                                    acuerdo con los nuevos Términos, puede rechazarlos; desafortunadamente, eso
                                    significa que ya no podrás utilizar los Servicios. Sin embargo, si utilizas los
                                    Servicios de alguna manera después de que un cambio en los Términos sea efectivo,
                                    significa que acepta todos los cambios. 
                                </li>
                                <br>
                                <li>
                                    LIMITACIONES. Apithy no será responsable por cualquier falla en los Servicios que
                                    resulte de o sea atribuible a: (a) los Sistemas del Proveedor de contenido, (b)
                                    fallas en los servicios de telecomunicaciones u otros servicios o equipos fuera de
                                    las instalaciones de Apithy, (c) los productos del Proveedor de contenido o de
                                    terceros, servicios, negligencia, actos u omisiones, (d) cualquier fuerza mayor o
                                    causa más allá del control razonable de Apithy, (e) mantenimiento programado, o;(f)
                                    acceso no autorizado, violación de cortafuegos u otro pirateo por parte de terceros.
                                </li>
                                <br>
                                <li>
                                    SISTEMAS. El Proveedor de contenido deberá obtener y operar todos los Sistemas
                                    necesarios para conectarse, acceder o utilizar los Servicios, y proporcionar todos
                                    los servicios de respaldo, recuperación y mantenimiento correspondientes. El
                                    proveedor de contenido se asegurará de que todos los sistemas sean compatibles con
                                    los servicios. El proveedor de contenido deberá mantener la integridad y seguridad
                                    de sus sistemas (físicos, electrónicos y de otro tipo).
                                </li>
                                <br>
                                <li>
                                    DERECHOS DE PROPIEDAD INTELECTUAL. Excepto por el Contenido del Proveedor de
                                    contenido, Apithy (y sus otorgantes de licencias) posee todos los derechos y títulos
                                    inherentes a los Servicios y todas las modificaciones, mejoras y actualizaciones a
                                    los mismos. Apithy se reserva todos los derechos no otorgados expresamente en este
                                    documento. 
                                </li>
                                <br>
                                <li>
                                    <p>TITULARIDAD DEL CONTENIDO Y LICENCIA.</p>
                                    <p>
                                        El Proveedor de contenido es el único titular de todos los derechos de propiedad
                                        intelectual inherentes a su contenido. Sin embargo, por el simple hecho de
                                        contratar los servicios, otorga a Apithy y sus afiliados una licencia limitada,
                                        mundial y no exclusiva para copiar, transmitir, distribuir, realizar
                                        públicamente y mostrar (a través de todos los medios ahora conocidos o creados
                                        posteriormente), y/o realizar trabajos derivados de su contenido, con el
                                        propósito de: (i) mostrar el contenido dentro del Servicio de Apithy; (ii)
                                        mostrar el contenido en sitios web y aplicaciones de terceros a través de una
                                        incrustación del mismo sujeto a sus opciones de privacidad; (iii) permitir que
                                        otros usuarios reproduzcan, pero sujeto a sus opciones de privacidad, el
                                        contenido; (iv) promover el Servicio de Apithy, siempre que haya puesto el
                                        contenido a disposición del público, y; (v) archivar o conservar el contenido
                                        para disputas, procedimientos legales o investigaciones.
                                    </p>
                                    <p>
                                        Por lo anterior, desde el momento de la contratación, el Proveedor de contenido
                                        garantiza que es el único titular de todos los derechos relacionados con el
                                        contenido respecto del cual solicitas la contratación de los servicios.
                                    </p>
                                    <p>
                                        En caso de cualquier reclamo por parte de terceros relacionado con el contenido,
                                        ya sea de forma total o parcial, Apithy podra reclamar al Proveedor de contenido
                                        los daños y perjuicios que le ocasione.
                                    </p>
                                    <p>
                                        La licencia que el Proveedor de contenido otorga a Apithy respecto de los
                                        contenidos, estará vigente por un periodo de tiempo de 5 años, contados estos a
                                        partir de que el contenido es distribuido y divulgado en la plataforma, ya sea
                                        en su versión original, o bien mediante el trabajo derivado que se realizó para
                                        la inclusión del mismo en la plataforma.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    INFORMACIÓN CONFIDENCIAL. Las Partes convienen que, no utilizarán ni divulgarán
                                    ninguna información que esté catalogada como confidencial y que pertenezca a la otra
                                    parte, a menos que se conceda el respectivo consentimiento para ell; en este caso,
                                    se deberá usar el cuidado razonable para proteger la Información Confidencial de la
                                    parte que corresponda.
                                </li>
                                <br>
                                <li>
                                    <p>
                                        REGALÍAS Y/O REMUNERACIÓN. Las partes acuerdan que, por la licencia que el
                                        Proveedor de contenido concede a Apithy mediante el presente acuerdo, recibirá
                                        en concepto de regalías y/o remuneración la cantidad total equivalente al
                                        porcentaje establecido en el Plan tarifario que haya seleccionado al momento de
                                        la contratación, y con base en el número de reproducciones que el(los)
                                        usuario(s) efectuen precisamente sobre el(los) contenido(s).
                                    </p>
                                    <p>
                                        Desde ahora queda pactado que, para realizar el cálculo del importe de regalías
                                        y/o remuneración al que tiene derecho el Proveedore de contenido, Apithy
                                        realizará un corte a más tardar los días 25 de cada mes respecto del número de
                                        reproducciones efectuadas del contenido, con la intención de generar el estado
                                        de cuenta en el que se refleje el saldo a pagar. Por ende, los periodos
                                        sucesivos para el cálculo de las siguientes regalías oscilara del día 26 del mes
                                        que concluye, al día 25 del mes siguiente. 
                                    </p>
                                    <p>
                                        Para los efectos anteriores, el Proveedor de contenido debe proporcionar a
                                        Apithy los datos bancarios necesarios para realizar mediante transferencia
                                        bancaria la liquidación de el(los) importe(s) que procedan, así como los
                                        documentos fiscales y legales que le sean solicitados.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    LÍMITE DE RESPONSABILIDAD. Con excepción de lo especificado aquí, los servicios se
                                    proporcionan "<i>tal cual</i>" sin garantía de ningún tipo. Apithy no garantiza que
                                    los
                                    servicios cumplen los requisitos del proveedor de contenido o que su operación sea
                                    ininterrumpida o libre de errores.
                                </li>
                                <br>
                                <li>
                                    <p>
                                        VIGENCIA. Este Acuerdo comenzará a partir de la fecha en que sea aceptado. Con
                                        respecto a un usuario de la Versión de pago, este Acuerdo continuará en vigor
                                        durante el plazo inicial especificado en el Plan (o si no se especifica dicho
                                        término, durante el ciclo de facturación), y se renovará de manera automática
                                        con base en el plan seleccionado. Sin embargo, cualquiera de las partes puede
                                        optar por no renovar este Acuerdo mediante notificación por escrito de dicha
                                        elección, con por lo menos un mes antes en que ocurra dihca renovación. 
                                    </p>
                                    <p>
                                        En el caso del Proveedor de contenido, éste será el único responsable de
                                        notificar correctamente a Apithy sobre su elección de no renovar
                                        automáticamente, siguiendo las instrucciones de cancelación disponibles en la
                                        cuenta de Apithy del Proveedor de contenido. Con respecto a un usuario de la
                                        Versión gratuita, este Acuerdo continuará vigente hasta que cualquiera de las
                                        partes finalice este Acuerdo con al menos 5 días hábiles de notificación por
                                        escrito a la otra parte.
                                    </p>
                                    <p>
                                        Igualmente, el Proveedor de contenido acepta desde ahora que Apithy tendrá la
                                        facultad para retirar de la plataforma el contenido que le fue proveído, en caso
                                        de que él mismo no tenga la recurrencia necesaria de explotación (según el
                                        criterio de Apithy), o bien cuando el tema que aborda el propio contenido no es
                                        de actualidad.
                                    </p>
                                </li>
                                <br>
                                <li>
                                    <p>
                                        LINEAMIENTOS EN MATERIA DE DERECHOS DE AUTOR (Copyright). Es política de Apithy,
                                        en materia de derechos de autor: (a) bloquear el acceso o eliminar Contenido que
                                        cree de buena fe, es material protegido por derechos de autor y que ha sido
                                        copiado y distribuido ilegalmente por cualquiera de sus afiliados, usuarios y/o
                                        proveedores de contenido, y; (b) eliminar e interrumpir el servicio a
                                        reincidentes.
                                    </p>
                                    <p>
                                        Si cualquier tercero considera que el Contenido que reside o es accesible a
                                        través de la plataforma infringe un derecho de autor, podrá enviar un <i>aviso
                                        de
                                        infracción de copyright</i> que contenga la siguiente información:
                                    </p>
                                    <ol type="a">
                                        <li>
                                            Una firma física o electrónica de una persona autorizada para actuar en
                                            nombre del propietario de los derechos de autor que presuntamente se ha
                                            infringido;
                                        </li>
                                        <li>
                                            Identificación de obras o materiales objeto de infracción;
                                        </li>
                                        <li>
                                            Identificación del Contenido que se alega que infringe, incluida la
                                            información sobre la ubicación del Contenido que el propietario de los
                                            derechos de autor desea eliminar, con detalles suficientes para que Apithy
                                            sea capaz de encontrar y verificar su existencia;
                                        </li>
                                        <li>
                                            Información de contacto sobre el notificador, incluida la dirección, el
                                            número de teléfono y, si está disponible, la dirección de correo
                                            electrónico;
                                        </li>
                                        <li>
                                            Una declaración de que el notificador cree de buena fe que el Contenido no
                                            está autorizado por el propietario de los derechos de autor, su agente o la
                                            ley, y;
                                        </li>
                                        <li>
                                            Una declaración bajo protesta de decir verdad, de que la información
                                            proporcionada es correcta y la parte notificante está autorizada para
                                            presentar la queja en nombre del propietario de los derechos de autor.
                                        </li>
                                    </ol>
                                    <p>
                                        Una vez que Apithy reciba dicha notificación de infracción, procederá conforme a
                                        lo siguiente:
                                    </p>
                                    <ol type="a">
                                        <li>
                                            Eliminará y deshabilitará el accesos al contenido del infractor;
                                        </li>
                                        <li>
                                            Notificará a la persona que proveyo dicho Contenido, y/o al usuario que
                                            recurrentemente lo usa.
                                        </li>
                                    </ol>
                                </li>
                                <br>
                                <li>
                                    ACUERDO TOTAL. Los términos y condciones aquí vertidos, junto con la política de
                                    privacidad de Apithy y los Planes tarifarios aplicables, constituyen el acuerdo
                                    total y reemplaza todas las negociaciones, entendimientos o acuerdos anteriores
                                    (orales o escritos) entre las partes sobre los servicios. En caso de conflicto o
                                    inconsistencia entre el Acuerdo y el Plan, los términos y condiciones de los Planes
                                    tarifarios prevalecerán y serán dominantes.
                                </li>
                                <br>
                                <li>
                                    JURISDICCIÓN. Este Acuerdo se regirá e interpretará de acuerdo con las leyes de los
                                    Estados Unidos Mexicanos; específicamente, con las leyes y tribunales de la Ciudad
                                    de México.
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import VeeValidate from 'vee-validate';
    import es from 'vee-validate/dist/locale/es';
    import {FormWizard, TabContent, WizardButton} from 'vue-form-wizard';
    import 'vue-form-wizard/dist/vue-form-wizard.min.css';
    import axios from 'axios';
    import { email } from 'vee-validate/dist/rules.esm'
    import { index } from "../../helpers";

    export default {
        name: "FastRegister",
        components: {
            FormWizard,
            TabContent,
            WizardButton,
        },
        props: [
            'countries_data',
            'email',
            'company'
        ],
        computed: {
            samePassword () {
                let password = _.get(this.register_form, ['form_data', 'user', 'password'])
                let password_confirmation = _.get(this.register_form, ['form_data', 'user', 'password_confirmation'])
                if (_.isEmpty(password) || _.isEmpty(password_confirmation)) {
                    return false
                }
                return password === password_confirmation
            },
            confirmation_code() {
                let digits = this.register_email_resend.digits_code;
                if(this.completed_confirmation_code) {
                    return '' + digits.one + digits.two + digits.three + digits.four;
                }
                else {
                    return null;
                }
            },
            completed_confirmation_code() {
                let digits = this.register_email_resend.digits_code;
                return (digits.one && digits.two && digits.three && digits.four);
            },
            login_validate() {
                if (!this.access_data.access || !this.access_data.password) {
                    return true;
                }
                if (this.errors.has('login_user') || this.errors.has('login_pass')) {
                    return true;
                }
                return false;
            },
            registerImage() {
                return '/images/resources/png/registro' + this.current_step + '.png';
            },
            terminos_y_condiciones() {
                return route('terminos-y-condiciones').toString();
            },
            accepted_legal_terms() {
                if (this.register_form.form_data.user_account) {
                    return !(this.legal.privacity.accepted && this.legal.customer_services.accepted);
                }
                if (this.register_form.form_data.company_account) {
                    return !(this.legal.privacity.accepted && this.legal.customer_services.accepted && this.legal.company_services.accepted);
                }
                if (this.register_form.form_data.partner_account) {
                    return !(this.legal.privacity.accepted && this.legal.partner_services.accepted);
                }
                return false;
            }
        },
        mounted() {
            this.enableValidator();
        },
        methods: {
            enableValidator () {
                const dictionary = {
                    es: {
                        messages: {
                            company_phone: () => !this.badPhone ? `El número de teléfono sólo debe contener 10 dígitos.` : `El número de teléfono debe contener solo dígitos`,
                        },
                        attributes: {
                            firstname: 'nombre',
                            lastname: 'apellido',
                            email: 'correo electrónico',
                            login_user: 'correo electrónico',
                            confirm_email: "correo electrónico",
                            phone: 'teléfono',
                            new_phone: 'teléfono',
                            confirm_new_phone: 'confirmar teléfono',
                            new_email: 'correo electrónico',
                            confirm_new_email: 'confirmar correo electrónico',
                            confirm_phone: 'teléfono',
                            country: 'país',
                            state: 'estado',
                            postal_code: 'código postal',
                            login_pass: 'contraseña',
                            pwd: 'contraseña',
                            pwd_confirmation: 'contraseña',
                            confirm_password: 'confirmar contraseña',
                            sector: 'sector',
                            size: 'tamaño',
                            company_name: 'nombre de la empresa',
                            company_website: 'página web de la empresa',
                            r_social: 'razón social',
                            company_rfc: 'RFC',
                            company_country: 'país',
                            company_state: 'estado',
                            company_postal_code: 'código postal',
                            valid_domain: 'dominios validos',
                            city: 'ciudad',
                            workspace: 'espacio de trabajo',
                            domain: 'dominio'
                        },
                        custom: {
                            login: {
                                email: {
                                    required: 'La dirección de correo electrónico es obligatoria.',
                                    email: 'No es una dirección de correo electrónico válida.',
                                    regex: 'No es una dirección de correo electrónico válida.'
                                },
                                password: {
                                    required: 'La contraseña es obligatoria.',
                                    min: 'La longitud mínima de la contraseña es de 6 caracteres.'
                                }
                            },
                            firstname: {
                                required: 'El nombre es obligatorio.',
                                alpha_spaces: 'El nombre sólo puede contener letras y espacios.',
                                min: 'La longitud mínima del nombre es de 2 caracteres.',
                                max: 'La longitud máxima del nombre es de 50 caracteres.',
                            },
                            lastname: {
                                required: 'El apellido es obligatorio.',
                                alpha_spaces: 'El apellido sólo puede contener letras y espacios.',
                                min: 'La longitud mínima del apellido es de 2 caracteres.',
                                max: 'La longitud máxima del apellido es de 50 caracteres.',
                            },
                            email: {
                                required: 'La dirección de correo electrónico es obligatoria.',
                                email: 'No es una dirección de correo electrónico válida.',
                                regex: 'No es una dirección de correo electrónico válida.'
                            },
                            confirm_email: {
                                required: 'La confirmación de la dirección de correo electrónico es obligatoria.',
                                email: 'No es una dirección de correo electrónico válida.',
                                confirmed: 'Las direcciones de correo electrónico no coinciden.'
                            },
                            email_confirmation: {
                                required: 'La confirmación de la dirección de correo electrónico es obligatoria.',
                                email: 'No es una dirección de correo electrónico válida.',
                                confirmed: 'Las direcciones de correo electrónico no coinciden.'
                            },
                            new_email: {
                                required: 'La dirección de correo electrónico es obligatoria.',
                                email: 'No es una dirección de correo electrónico válida.',
                                regex: 'No es una dirección de correo electrónico válida.'
                            },
                            confirm_new_email: {
                                required: 'La confirmación de la dirección de correo electrónico es obligatoria.',
                                email: 'No es una dirección de correo electrónico válida.',
                                confirmed: 'Las direcciones de correo electrónico no coinciden.'
                            },
                            phone: {
                                required: 'El número de teléfono es obligatorio.',
                                digits: 'El número de teléfono sólo debe contener 10 dígitos.',
                                numeric: 'El número de teléfono sólo debe contener números.',
                            },
                            confirm_phone: {
                                required: 'El número de teléfono es obligatorio.',
                                digits: 'El número de teléfono sólo debe contener 10 dígitos.',
                                numeric: 'El número de teléfono sólo debe contener números.',
                                confirmed: 'Los números de teléfono no coiciden.',
                            },
                            new_phone: {
                                required: 'El número de teléfono es obligatorio.',
                                digits: 'El número de teléfono sólo debe contener 10 dígitos.',
                                numeric: 'El número de teléfono sólo debe contener números.',
                            },
                            confirm_new_phone: {
                                required: 'El número de teléfono es obligatorio.',
                                digits: 'El número de teléfono sólo debe contener 10 dígitos.',
                                numeric: 'El número de teléfono sólo debe contener números.',
                                confirmed: 'Los números de teléfono no coiciden.',
                            },
                            country: {
                                required: 'El país es obligatorio.'
                            },
                            password: {
                                required: 'La contraseña es obligatoria.',
                                min: 'La longitud mínima de la contraseña es de 6 caracteres.'
                            },
                            confirm_password: {
                                required: 'La confirmación de contraseña es obligatoria.',
                                confirmed: 'Las contraseñas no coinciden.'
                            },
                            pwd: {
                                required: 'La contraseña es obligatoria.',
                                min: 'La longitud mínima de la contraseña es de 6 caracteres.'
                            },
                            pwd_confirmation: {
                                required: 'La confirmación de contraseña es obligatoria.',
                                confirmed: 'Las contraseñas no coinciden.'
                            },
                            company_name: {
                                required: 'El nombre de la empresa es obligatorio.',
                                max: 'La longitud máxima del nombre de la empresa son 25 caracteres.'
                            },
                            r_social: {
                                required: 'La razón social de la empresa es obligatoria.',
                            },
                            company_rfc: {
                                required: 'El RFC de la empresa es obligatorio.',
                                alpha_num: 'El RFC de la empresa sólo puede contener letras y números.',
                                regex: 'El RFC de la empresa sólo puede contener letras y números.'
                            },
                            company_country: {
                                required: 'El país es obligatorio.'
                            },
                            company_state: {
                                required: 'El estado es obligatorio.'
                            },
                            domain: {
                                required: 'El dominio de la empresa es obligatorio.',
                                regex: 'El dominio de la empresa sólo puede contener letras, números, guiones medios y guiones bajos.'
                            }
                        }
                    },
                    en: {
                        messages: {
                            company_phone: () => !this.badPhone ? `The phone number must only contain 10 digits.` : `The phone number must only digits.`,
                        }
                    }
                }
                VeeValidate.Validator.localize(dictionary);
                VeeValidate.Validator.localize('es', es)
                VeeValidate.Validator.extend('custom_email', {
                    validate: value => {
                        if (!this.isPhone) {
                            return email.validate(value)
                        }
                        return true
                    }
                })
                VeeValidate.Validator.extend('company_phone', {
                    validate: value => {
                        if (this.pattern.test(value)) {
                            this.isPhone = true
                            this.badPhone = false
                            return value.length === 10;
                        } else if (!_.includes(value, '@') && this.strPattern.test(value)) {
                            this.isPhone = true
                            this.badPhone = true
                            return false
                        }
                        this.isPhone = false
                        return true
                    }
                });
                //Email, Company Name, RFC, Legal Name,account_name

                VeeValidate.Validator.extend('verify_password', {
                    getMessage: field => `La contraseña debe contener al menos: 6 caracteres, 1 letra mayúscula, 1 letra minúscula y 1 número.`,
                    validate: value => {
                        var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})");
                        return strongRegex.test(value);
                    }
                });

                this.$validator.extend('verify_email_exist', this.getAsyncValidatorConfig("email"));
                this.$validator.extend('verify_company_name', this.getAsyncValidatorConfig("company_name", "name"));
                this.$validator.extend('verify_rfc', this.getAsyncValidatorConfig("company_rfc", "rfc"));
                this.$validator.extend('verify_legal_name', this.getAsyncValidatorConfig("r_social", "legal_name"));
                this.$validator.extend('verify_account_name', this.getAsyncValidatorConfig("domain", "account_name"));
            },
            resend() {
                if (this.register_email_resend.resend < 3) {
                    axios({
                        url: '/register/confirm/resend',
                        method: 'GET',
                        params: {
                            confirmation_user_id: this.user.id
                        }
                    })
                        .then(response => {
                            this.register_email_resend.resend++;
                            switch (this.register_email_resend.resend) {
                                case 1:
                                    this.register_email_resend.timer = 60;
                                    break;
                                case 2:
                                    this.register_email_resend.timer = 180;
                                    break;
                                default:
                                    this.register_email_resend.timer = 60;
                                    break;
                            }
                            this.countdown();
                        })
                        .catch(error => {
                            console.log(error)
                        })
                }
            },
            countdown() {
                setTimeout(() => {
                    if (this.register_email_resend.timer > 0) {
                        this.register_email_resend.timer--;
                        this.countdown();
                    }
                },1000);
            },
            select(evt) {
                evt.target.select();
            },
            focusNext(evt) {
                if(this.completed_confirmation_code) {
                    this.confirmValidationCode();
                }

                if(evt.keyCode > 47 && evt.keyCode < 58 && !evt.shiftKey) {
                    let next = evt.target.dataset.next;
                    let element = this.$refs[next];

                    if(element) {
                        element.focus();
                        element.select();
                    }
                }
            },
            getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i <ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            login() {
                this.register_welcome.loading = true;
                axios.post('/login',this.access_data)
                    .then(response => {

                        let public_experience = this.getCookie('public_experience');
                        let operation_result = this.getCookie('operation_result');

                        if (!_.isEmpty(public_experience) && !_.isEmpty(operation_result)) {
                            public_experience = JSON.parse(public_experience);
                            operation_result = JSON.parse(operation_result);
                            if (public_experience.event === 'enroll_user') {
                                window.location.href = '/experiences/' + public_experience.experience + '/view';
                            }
                            else if (public_experience.event === 'append_to_cart') {
                                window.location.href = '/experiences/' + public_experience.experience + '/preview';
                            } else {
                                window.location.href = window.location.origin + '/getting-started'
                            }
                        } else {
                            window.location.href = window.location.origin + '/getting-started'
                        }
                    })
                    .catch(error => {
                        this.register_welcome.loading = false;
                        if (!!error.response) {
                            if(error.response.data.errors.access.length > 0) {
                                this.register_welcome.message = error.response.data.errors.access[0];
                            }
                            else {
                                this.register_welcome.message = error.response.data.message;
                            }
                        } else {
                            console.log(error);
                        }
                    })
            },
            updateConfirmationInfo() {
                let data = null;

                this.$validator.validateAll().then(result => {
                    if(result) {
                        if(this.wrong.phone) {
                            data = this.wrong.new_phone;
                        }
                        else {
                            data = this.wrong.new_email;
                        }

                        axios({
                            url: '/update-contact-info',
                            method: 'POST',
                            params: {
                                user_id: this.user.id,
                                update: data
                            }
                        })
                            .then(response => {
                                this.user.email = this.wrong.new_email;
                                this.user.phone = this.wrong.new_phone;

                                this.wrong.counter++;
                                this.wrong.new_email = null;
                                this.wrong.new_phone = null;
                                this.wrong.confirm_new_email = null;
                                this.wrong.confirm_new_phone = null;

                                this.register_email_resend.class = '';
                                this.register_email_resend.message = null;

                                this.register_email_resend.digits_code.one = null;
                                this.register_email_resend.digits_code.two = null;
                                this.register_email_resend.digits_code.three = null;
                                this.register_email_resend.digits_code.four = null;

                                this.showRegister('email_resend');
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                });
            },
            backToResend() {
                this.showRegister('email_resend');
            },
            isNumber: function (evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                    ;
                } else {
                    return true;
                }
            },
            addEmailDomain() {
                let valid_domains = this.register_form.form_data.company.valid_domains;
                let new_domain = this.register_form.new_domain;
                let template = {domain: ''};

                if (this.fields.$step3.valid_domain.valid) {
                    template.domain = new_domain;
                    valid_domains.push(template);
                    new_domain = '';
                }
            },
            removeEmailDomain(index) {
                let valid_domains = this.register_form.form_data.company.valid_domains;
                valid_domains.splice(index, 1);
            },
            passwordStrength(value) {
                let pwd = this.register_form.form_data.user.password;
                let pwd_strong = this.password_strength;
                let el = this.$refs.password_progress_bar;
                let strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\.\,!@#\$%\^&\*])(?=.{8,})");
                let mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{7,})");
                let lowRegex = new RegExp("^([A-Za-z]{6,})");

                if (pwd.length > 0) {
                    if (strongRegex.test(pwd)) {
                        pwd_strong = 100;
                        el.style.backgroundColor = "#205500"; //Dark Green
                        el.style.width = pwd_strong + '%';
                    } else if (mediumRegex.test(pwd)) {
                        pwd_strong = 75;
                        el.style.backgroundColor = "#00E33F"; //Green
                        el.style.width = pwd_strong + '%';
                    } else if (lowRegex.test(pwd)) {
                        pwd_strong = 50;
                        el.style.backgroundColor = "#FFD800"; //Yellow
                        el.style.width = pwd_strong + '%';
                    } else {
                        pwd_strong = 25;
                        el.style.backgroundColor = "#FF2E09"; //Red
                        el.style.width = pwd_strong + '%';
                    }
                } else {
                    pwd_strong = 0;
                    el.style.backgroundColor = "#FF2E09"; //Red
                    el.style.width = pwd_strong + '%';
                }
            },
            setSuccessColorStep() {
                $("div[aria-selected='true'] div").parents("li").prev().find(".wizard-icon-circle")
                    .css(
                        {
                            "background-color": "limegreen",
                            "border-radius": "100px", "border-color": "limegreen"
                        });

                $(".wizard-icon").html("");

            },
            getAsyncValidatorConfig(field_name, db_field_name = null) {
                return {
                    getMessage: (field, params, data) => data.message,
                    validate: value => {
                        db_field_name = (db_field_name !== null) ? db_field_name : field_name;
                        return axios({
                            method: 'post',
                            url: '/signup/validate',
                            params: {
                                [db_field_name]: value
                            }
                        }).then(response => {
                            return {
                                valid: true
                            }
                        }).catch(error => {
                            return {
                                valid: false,
                                data: {
                                    message: error.response.data.errors[db_field_name][0]
                                }
                            }
                        });
                    }
                }
            },
            continueCompanySpace() {
                let domain = this.register_type.company_domain;
                axios({
                    url: '/signup/validate',
                    method: 'POST',
                    params: {
                        domain_to_join: domain
                    }
                })
                    .then(response => {
                        this.register_type.dont_exist = false;
                        document.location.href = '/login';
                    })
                    .catch(errors => {
                        this.register_type.dont_exist = true;
                    });
            },
            continueCompany() {
                this.register_form.steps = this.company_steps;
                this.register_form.type.user = false;
                this.register_form.type.company = true;
                this.showRegister('form');
            },
            continueUser() {
                this.register_form.steps = this.user_steps;
                this.register_form.type.user = true;
                this.register_form.type.company = false;
                this.showRegister('form');
            },
            showRegister(target) {
                switch (target) {
                    case 'form':
                        this.register_type.show = false;
                        this.register_form.show = true;
                        this.register_email_resend.show = false;
                        this.register_welcome.show = false;
                        this.wrong.show = false;
                        let token_field = document.getElementsByName('_token')[0];
                        this.register_form.form_data.token = {name: token_field.name, value: token_field.value};
                        break;
                    case 'email_resend':
                        this.register_type.show = false;
                        this.register_form.show = false;
                        this.register_email_resend.show = true;
                        this.register_welcome.show = false;
                        this.wrong.show = false;

                        if(this.getContactPreference() === 'sms') {
                            this.$nextTick(() => this.$refs.one.focus())
                        }

                        break;
                    case 'welcome':
                        this.register_type.show = false;
                        this.register_form.show = false;
                        this.register_email_resend.show = false;
                        this.register_welcome.show = true;
                        this.wrong.show = false;
                        break;
                    case 'wrong':
                        this.wrong.show = true;
                        this.register_type.show = false;
                        this.register_form.show = false;
                        this.register_email_resend.show = false;
                        this.register_welcome.show = false;
                        break;
                    default:
                        this.register_type.show = true;
                        this.register_form.show = false;
                        this.register_email_resend.show = false;
                        this.register_welcome.show = false;
                        this.wrong.show = false;
                        break;
                }

                if (this.current_step > 1) {
                    this.current_step--;
                }

                this.errors.clear();
            },
            prevStep(props) {
                /*
                if (props.activeTabIndex) {
                    let scope = 'step' + (props.activeTabIndex + 1);
                    this.$validator.validateAll(scope).then((result) => {
                        if (result) {
                            props.prevTab();
                        }
                    });
                }
                */
                props.prevTab();
            },
            nextStep(props) {
                if (props.activeTabIndex < 4) {
                    let scope = 'step' + (props.activeTabIndex + 1);
                    this.$validator.validateAll(scope).then((result) => {
                        if (result) {
                            this.current_step++;
                            props.nextTab();
                            $(".wizard-icon").html("");
                            var $this = this;
                            setTimeout(function () {
                                $this.setSuccessColorStep();
                            }, 300);
                        }
                    });
                } else {
                    props.nextTab();
                }
            },
            stepValidator() {
                /*
                let scope = 'step' + (this.$refs.formWizard.activeTabIndex + 1);
                this.$validator.validateAll(scope);
                if (this.errors.items.length === 0) {
                    return true;
                }
                return false;

                */

                return true;
            },
            finishRegister() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.prepareData();
                    }
                });
            },
            prepareData() {
                let base_url = document.location.origin;

                let user = this.register_form.form_data.user;
                let company = this.register_form.form_data.company;

                let user_params = {
                    name: user.firstname,
                    email: user.email,
                    password: user.password,
                    password_confirmation: user.password_confirmation,
                    surname: user.lastname,
                    phone: user.phone,
                    phone_code: user.phone_code,
                    company_id: '',
                    area_id: '',
                    position_id: '',
                    register_type: "on_site",
                    role_id: 7,
                    activated: 0,
                    contact_preference: this.register_form.form_data.contact_preferences,
                };

                let company_params = {
                    country_id: company.country,
                    company_province: company.state,
                    name: company.name,
                    short_name: company.name.split(' ')[0],
                    legal_name: company.r_social,
                    account_name: company.domain,
                    site_url: company.website,
                    contact_email: user.email,
                    contact_phone: user.phone,
                    rfc: company.rfc,
                    size: company.size,
                    zip: company.postal_code,
                    city: company.city,
                    valid_domains: company.valid_domains,
                    num_employees_min: company.size.split(' a ')[0],
                    num_employees_max: company.size.split(' a ')[1],
                    status: 0
                };

                if (this.register_form.form_data.company_account) {
                    user_params.company_info = company_params;
                    user_params.role_id = 9;
                    user_params.activated = 0;
                }

                if (this.register_form.form_data.partner_account) {
                    //user_params.company_info = company_params;
                    user_params.role_id = 8;
                    user_params.activated = 0;
                }

                this.send(base_url + '/register', 'POST', user_params);
            },
            send(url, method, params) {
                params[this.register_form.form_data.token.label] = this.register_form.form_data.token.value;
                axios({
                    method: method,
                    url: url,
                    params: params
                }).then((response) => {
                    this.user = response.data;
                    this.show_confirm_box = false;
                    this.user.confirmation_url = "/register/confirm/resend/" + this.user.confirmation_code;
                    this.showRegister('email_resend');
                }).catch((error) => {
                    let errorList = index.hasErrors(error)
                    if (errorList) {
                        index.setErrors(this.errors, errorList)
                    }

                    let errors_list = error.response.data.errors;

                    if (Array.isArray(errors_list)) {
                        this.server_errors.data = errors_list;
                    } else {
                        this.server_errors.data = new Array(errors_list);
                    }

                    this.server_errors.show = true;

                });
            },
            getContactPreference() {
                return this.register_form.form_data.contact_preferences;
            },
            setContactPreference(value) {
                this.register_form.form_data.contact_preferences = value;

                // set form for wrong contact email or phone
                if(value === 'sms' || value === 'whatsapp') {
                    this.wrong.phone = true;
                }
            },
            confirmValidationCode() {
                axios({
                    method: 'GET',
                    url: '/register/confirm/phone/' + this.confirmation_code,
                    params: {
                        user_id: this.user.id
                    }
                })
                    .then(response => {
                        let status = response.data.status;
                        let message = response.data.message;
                        this.register_email_resend.message = message;

                        if(status === 200) {
                            setTimeout(() => {
                                this.showRegister('welcome');
                            },2000);
                            this.register_email_resend.class = 'text-success';
                        } else {
                            this.register_email_resend.class = 'text-danger';
                        }
                    })
                    .catch(error => {
                        let message = (error.response.data) ? error.response.data.message : error.message;
                        let status = (error.response.data) ? error.response.data.status : error.status;

                        this.register_email_resend.message = message;
                        this.register_email_resend.class = 'text-red';
                    })
            },
            setAccount(account) {
                switch (account) {
                    case 'company':
                        this.register_form.form_data.company_account = true;
                        this.register_form.form_data.partner_account = false;
                        this.register_form.form_data.user_account = false;
                        break;
                    case 'partner':
                        this.register_form.form_data.company_account = false;
                        this.register_form.form_data.partner_account = true;
                        this.register_form.form_data.user_account = false;
                        break;
                    default:
                        this.register_form.form_data.company_account = false;
                        this.register_form.form_data.partner_account = false;
                        this.register_form.form_data.user_account = true;
                }
            }
        },
        data() {
            return {
                access_data: {
                    access: null,
                    password: null
                },
                wrong: {
                    counter: 0,
                    show: false,
                    phone: false,
                    new_phone: null,
                    confirm_new_phone: null,
                    new_email: null,
                    confirm_new_email: null
                },
                show_confirm_box: false,
                legal: {
                    privacity: {
                        id: '#privacy',
                        label: 'Aviso de Privacidad',
                        accepted: false,
                    },
                    customer_services: {
                        id: '#terms_conditions',
                        label: 'Condiciones de Servicio a Cliente',
                        accepted: false,
                    },
                    company_services: {
                        id: '#service_agreement',
                        label: 'Acuerdos de servicio',
                        accepted: false,
                    },
                    partner_services: {
                        id: '#service_agreement_partner',
                        label: 'Acuerdos de servicio',
                        accepted: false,
                    }
                },
                user: [],
                current_step: 1,
                server_errors: {
                    show: false,
                    data: []
                },
                register_email_resend: {
                    resend: 0,
                    timer: 0,
                    show: false,
                    message: null,
                    class: '',
                    digits_code: {
                        one: null,
                        two: null,
                        three: null,
                        four: null
                    }
                },
                password_strength: 0,
                register_form: {
                    show: true,
                    type: {
                        company: false,
                        user: false
                    },
                    new_domain: '',
                    form_data: {
                        user_account: true,
                        company_account: false,
                        partner_account: false,
                        contact_preferences: 'mail',
                        token: {
                            label: '',
                            value: ''
                        },
                        user: {
                            firstname: '',
                            lastname: '',
                            email: '',
                            refer_email: this.email,
                            phone: '',
                            confirm_phone: '',
                            phone_code: 52,
                            country: 484,
                            city: '',
                            state: '',
                            postal_code: '',
                            password: '',
                            password_confirmation: ''
                        },
                        company: {
                            sector: '',
                            size: '',
                            name: '',
                            website: '',
                            r_social: '',
                            rfc: '',
                            country: 484,
                            state: '',
                            postal_code: '',
                            workspace: '',
                            domain: '',
                            city: '',
                            valid_domains: []
                        }
                    }
                },
                register_type: {
                    show: false,
                    company_domain: '',
                    dont_exist: false
                },
                register_welcome: {
                    show: false,
                    loading: false,
                    message: null,
                },
                countries: this.countries_data,
                phone_code_list: [
                    {value: 52},
                    {value: 53},
                    {value: 54},
                ],
                sectors: [
                    'Entidades financieras',
                    'Mercados financieros',
                    'Sector inmobiliario',
                    'Seguros',
                    'Servicios financieros',
                    'Comercio electrónico',
                    'Tecnología de la información',
                    'Finanzas',
                    'Salud e higiene',
                    'Mobiliario',
                    'Alimentos e insumos'
                ],
                sizes: [
                    {label: '1 - 10 personas', value: '1 a 10'},
                    {label: '11 - 50 personas', value: '11 a 50'},
                    {label: '51 - 250 personas', value: '51 a 250'},
                    {label: '251 - 500 personas', value: '251 a 500'},
                    {label: '501 - 1000 personas', value: '501 a 1000'},
                    {label: '1001 - 4000 personas', value: '1001 a 4000'},
                    {label: '4000 personas o más', value: '4000 a 10000'},
                ],
                isPhone: true,
                badPhone: false,
                pattern: /^-?[0-9]{4}\.?\d*$/,
                strPattern: /^-?[0-9]{5}\.?\d*\.?\w*$/,
                validators: {
                    login: {
                        email: 'required|company_phone|custom_email',
                        password: {
                            required: true,
                            min: 6,
                            //verify_password: true
                        }
                    },
                    firstname: {
                        required: true,
                        alpha_spaces: true,
                        min: 2,
                        max: 50
                    },
                    lastname: {
                        required: true,
                        alpha_spaces: true,
                        min: 2,
                        max: 50
                    },
                    email: {
                        required: true,
                        email: true,
                        verify_email_exist: true,
                        regex: '^[a-z0-9_+]([a-z0-9_+.]+[a-z0-9_+])?\\@[a-z0-9.-]+$'
                    },
                    email_confirmation: {
                        required: true,
                        email: true,
                        confirmed: 'email'
                    },
                    new_email: {
                        required: true,
                        email: true,
                        verify_email_exist: true,
                        regex: '^[a-z0-9_+]([a-z0-9_+.]+[a-z0-9_+])?\\@[a-z0-9.-]+$'
                    },
                    confirm_new_email: {
                        required: true,
                        email: true,
                        confirmed: 'new_email'
                    },
                    registered_phone: {
                        required: false,
                        digits: 10,
                        numeric: true,
                    },
                    confirm_registered_phone: {
                        required: false,
                        digits: 10,
                        numeric: true,
                        confirmed: 'phone',
                    },
                    phone: {
                        required: true,
                        digits: 10,
                        numeric: true,
                    },
                    confirm_phone: {
                        required: true,
                        digits: 10,
                        numeric: true,
                        confirmed: 'phone',
                    },
                    new_phone: {
                        required: true,
                        digits: 10,
                        numeric: true,
                    },
                    confirm_new_phone: {
                        required: true,
                        digits: 10,
                        numeric: true,
                        confirmed: 'new_phone',
                    },
                    country: {
                        required: true
                    },
                    pwd: {
                        required: true,
                        min: 6,
                        verify_password: true
                    },
                    pwd_confirmation: {
                        required: true,
                        confirmed: 'password'
                    },
                    company_name: {
                        required: true,
                        max: 20,
                        verify_company_name: true,
                    },
                    r_social: {
                        required: true,
                        verify_legal_name: true
                    },
                    company_rfc: {
                        required: true,
                        alpha_num: true,
                        regex: '^((([a-zñ]|[A-ZÑ]){3,4})(([0-9]{2})([0][1-9]|[1][0-2])(([0][1-9]|[12][0-9])|[3][01]))(([a-zñ0-9]|[A-ZÑ0-9]){3}))$',
                        verify_rfc: true
                    },
                    company_country: {
                        required: true
                    },
                    company_state: {
                        required: true
                    },
                    valid_domain: {
                        regex: '^(?!:\\/\\/)([a-zA-Z0-9-]+\\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\\.[a-zA-Z]{2,64}?$',
                    },
                    workspace: {
                        required: true,
                        alpha_dash: true,

                    },
                    domain: {
                        required: true,
                        regex: '^[0-9A-Za-z\\_\\-]+$',
                        verify_account_name: true
                    }
                }
            };
        }
    }
</script>

<style scoped>
    .width-100 {
        width: 100%;
    }

    .width-83 {
        width: 83%;
    }

    .width-84 {
        width: 84%;
    }

    .width-70 {
        width: 70%;
    }

    .override-btn {
        background-color: transparent !important;
        color: #FFA81E;
        font-weight: bold;
    }

    .account-type {
        border: #b2b2b2 1px solid;
        border-radius: 5px;
        cursor: pointer;
        height: 70px;
        margin: 0;
    }

    .account-type.active {
        color: #ffffff;
        background-color: #1C6BF9;
    }

    .contact-preference {
        border: #b2b2b2 1px solid;
        border-radius: 10px;
        cursor: pointer;
        height: 50px;
        padding: 12px;
    }

    .contact-preference.active {
        color: #ffffff;
        background-color: #1C6BF9;
    }

    .phone-code {
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
    }

    .company-account-form {
        margin-top: 40px;
    }

    .padding-right-4p {
        padding: 0px 0px 0 2%;
    }

    .form-control:focus {
        outline: 0 !important;
        border-color: initial;
        box-shadow: none;
    }

    .is-valid {
        border-color: #28a745 !important;
    }

    .text-danger {
        border-color: red !important;
    }

    .confirmation-code-input {
        padding: 10px !important;
        text-align: center;
        font-size: 60px;
        margin: 0 auto;
        height: 80px;
        width: 60px;
    }

    .apithy-with-icon span.icon {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom-left-radius: 5px !important;
        border-top-left-radius: 5px !important;
        background-color: transparent !important;
        border: solid 1px #B3B3B3;
        border-right: none !important;
        padding: 10px 10px;
    }

    .apithy-with-icon input {
        margin-top: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: none !important;
        padding-left: 5px !important;
    }

    .one-step {
        height: 300px;
    }

    .container-domains .domain-item .pre {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        background-color: #FFA81E;
    }

    .container-domains .domain-item .mdl {
        border-top-left-radius: 0px !important;
        border-bottom-left-radius: 0px !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        background-color: #FFE066;
    }

    .container-domains .domain-item .nxt {
        padding-left: 2rem;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        cursor: pointer;
    }

    .back-btn > button {
        font-size: 1.2rem !important;
        background-color: transparent !important;
        color: #FFA81E !important;
        border: none !important;
        box-shadow: none !important;
        cursor: pointer;
    }

    .continue-btn-valid {
        color: #FFA81E !important;
    }

    .continue-btn-invalid {
        color: #B2B2B2 !important;
    }

    .continue-btn > button {
        font-size: 1.2rem !important;
        border: none !important;
        cursor: pointer;
        color: white !important;
    }

    .register-form {
        min-height: 620px;
    }

    .contenedor-persona-apithy {
        position: relative;
    }

    .persona-apithy {
        position: absolute;
        left: 10px;
        bottom: 0px;
        height: 70%;
    }

    .vue-form-wizard {
        margin: 0 auto;
    }

    .container {
        background-color: white;
    }

    .register {
        margin-top: 50px;
        text-align: center;
    }

    .wizard-card-footer button {
        background-color: #FFA81E;
        color: white;
        border-radius: 5px;
        border: none;
        font-family: 'AvenirRoman', sans-serif;
        box-shadow: 2px 2px 6px 0 rgba(88, 88, 88, 0.41);
        font-size: .8rem;
        padding: 5px 10px 5px 10px;
        margin: 30px auto 10px auto;
        display: block;
    }

    .company-space-join {
        margin-top: 50px;
    }

    .btn-primary-alter {
        color: #FFFFFF !important;
        background-color: #FFA81E !important;
    }

    .user-register-button-top {
        background-color: #FFA81E;
        color: white;
        border-radius: 40px;
        border: none;
        font-family: 'AvenirRoman', sans-serif;
        box-shadow: 2px 2px 6px 0 rgba(88, 88, 88, 0.41);
        border-top-left-radius: 40px !important;
        border-bottom-left-radius: 40px !important;
        font-size: .8rem;
        padding: 10px 50px 10px 50px;
        cursor: pointer;
    }

    .custom-select.is-valid, .form-control.is-valid, .was-validated .custom-select:valid, .was-validated .form-control:valid {
        border-color: #28a745 !important;
    }

    input:focus {
        z-index: 1 !important;
    }

    input {
        border: solid 1px !important;
        text-align: left;
        border-radius: 5px !important;
        border: solid 1px lightgrey;
    }

    input.text-danger {
        border: solid 1px red !important;
        border-left: none !important;
    }
    input.text-red {
        border: solid 1px red !important;
        color: red !important;
    }
    .text-red {
        color: red !important;
    }

    span.text-danger {
        color: red;
        font-size: 0.8rem;
        padding-left: 10px;
    }

    span.head {
        font-size: 1.5rem;
        font-weight: bold;
        font-family: 'Exo 2', sans-serif;
        display: block;
    }

    .h2, h2 {
        font-size: 1.5rem;
        font-weight: bold;
        font-family: 'Exo 2', sans-serif;
        margin-bottom: 15px;
    }

    .h3, h3 {
        font-size: 1.7rem;
        font-family: 'Exo 2', sans-serif;
        font-weight: bold
    }

    .select-rounded {
        border-radius: 10px !important;
    }

    .password-strength {
        height: 10px;
        margin-top: 0.5rem;
    }

    .password-strength {
        width: 99% !important;
        margin-left: 4px !important;
    }

    .domain-url {
        display: block;
        margin-top: 10px;
        font-weight: bold;
        padding-left: 5px;
        font-size: 1.2rem;
    }

    .confirm-code-action-inner {
        background-color: #FFF5B0;
        padding: 10px;
        margin-left: 5px;
        border-radius: 5px;
    }

    .confirm-code-action-inner a {
        background-color: orange;
        padding: 10px 20px;
        border-radius: 5px;
        color: white !important;
        text-decoration: none;
        margin-bottom: 10px;
        display: block;
        width: 180px;
        text-align: center;
    }

    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 60px;
        height: 60px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    .social-connect h3{
        margin: 0 auto 30px !important
    }

    .social-login-item{
        width: 222px;
        height: 49px;
        display: block;
        background-repeat: no-repeat !important;
        margin: 0 auto 20px;
    }

    .google-login-button{
        background: url('/images/social_login/google_login.png');
    }
    .facebook-login-button{
        background: url('/images/social_login/facebook_login.png');
    }

    .linkedin-login-button{
        background: url('/images/social_login/linkedin_login.png');
    }

    .legal-legend{
        font-size:13px;
        text-align:center;
        display:block;
        z-index: 1000;
    }
    .legal-legend a{
        color:#11CBFF;
        z-index:100;
    }

    .vue-form-wizard .wizard-header{
        display: none !important;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media all and (max-width: 641px) {
        .stepTitle {
            display: none;
        }

        .password-strength {
            width: 99% !important;
            margin-left: 2px !important;
        }

        .input-group {
            margin-bottom: 10px;
        }

        .fancybox-close-small {
            position: fixed !important;
        }

        .persona-apithy{
            display: none;
        }
    }
</style>
