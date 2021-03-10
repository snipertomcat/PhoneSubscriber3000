<template>
    <div id="register-form" class="container">
        <div>
            <div class="row register" v-if="register_type.show">
                <div class="col-sm-10 col-md-6 text-left company-register"> // Remover el false
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

            <div class="row register-form" v-if="register_form.show">
                <form-wizard class="col-sm-8"
                             title=""
                             subtitle=""
                             color="#FFA81E"
                             ref="formWizard"
                             errorColor="#FC2D24"
                             :validate-on-back="true"
                             stepSize="xs">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="server_errors.show">
                        <span v-for="(error, index) in server_errors.data"><strong>Error: </strong>{{error}}</span>
                        <button type="button" class="close" aria-label="Close" @click="server_errors.show = false">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <tab-content title="Datos" icon="" :before-change="stepValidator">
                        <form data-vv-scope="step1">
                            <div class="row justify-content-md-center">
                                <div class="col-sm-12">
                                    <h2>¿Como te llamas?</h2>
                                    <p>Así es como te verán tus compañeros en el espacio de trabajo.</p>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                        'input-group-text icon':true,
                                                        'text-danger': errors.has('step1.firstname'),
                                                        'is-valid': (fields.$step1 && fields.$step1.firstname.valid)
                                                        }">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="text"
                                                           name="firstname"
                                                           placeholder="Nombre(s)"
                                                           :class="{
                                                       'form-control':true,
                                                       'text-danger': errors.has('step1.firstname'),
                                                       'is-valid': (fields.$step1 && fields.$step1.firstname.valid)
                                                   }"
                                                           v-model="register_form.form_data.user.firstname"
                                                           v-validate="validators.step1.firstname">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step1.firstname')">{{ errors.first('step1.firstname') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                        'input-group-text icon':true,
                                                        'text-danger': errors.has('step1.lastname'),
                                                        'is-valid': (fields.$step1 && fields.$step1.lastname.valid)
                                                        }">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="text"
                                                           name="lastname"
                                                           placeholder="Apellido(s)"
                                                           :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.lastname'),
                                                       'is-valid': (fields.$step1 && fields.$step1.lastname.valid)
                                                   }"
                                                           v-model="register_form.form_data.user.lastname"
                                                           v-validate="validators.step1.lastname">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step1.lastname')">{{ errors.first('step1.lastname') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div class="row justify-content-md-center">
                                <div class="col-sm-12">
                                    <h2>Datos de contacto</h2>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('step1.email'),
                                                       'is-valid': (fields.$step1 && fields.$step1.email.valid)
                                                        }">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="email"
                                                           name="email"
                                                           ref="email"
                                                           placeholder="Correo electrónico"
                                                           :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.email'),
                                                       'is-valid': (fields.$step1 && fields.$step1.email.valid)
                                                   }"
                                                           v-validate="validators.step1.email">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step1.email')">{{ errors.first('step1.email') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('step1.confirm_email'),
                                                       'is-valid': (fields.$step1 && fields.$step1.confirm_email.valid)
                                                        }">
                                                        <i class="fas fa-envelope"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="email"
                                                           name="confirm_email"
                                                           placeholder="Confirmar correo"
                                                           :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.confirm_email'),
                                                       'is-valid': (fields.$step1 && fields.$step1.confirm_email.valid)
                                                   }"
                                                           v-model="register_form.form_data.user.email"
                                                           v-validate="validators.step1.email_confirmation">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step1.confirm_email')">{{ errors.first('step1.confirm_email') }}</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-md-left">
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('step1.phone'),
                                                       'is-valid': (fields.$step1 && fields.$step1.phone.valid)
                                                        }">
                                                        <i class="fas fa-phone"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="text"
                                                           name="phone"
                                                           placeholder="Teléfono"
                                                           :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.phone'),
                                                       'is-valid': (fields.$step1 && fields.$step1.phone.valid)
                                                   }"
                                                           v-model="register_form.form_data.user.phone"
                                                           v-validate="validators.step1.phone">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step1.phone')">{{ errors.first('step1.phone') }}</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-md-center">
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('step1.postal_code'),
                                                       'is-valid': (fields.$step1 && fields.$step1.postal_code.valid)
                                                        }">
                                                        CP
                                                    </span>
                                                </div>
                                                <div class="width-83">
                                                    <input type="text"
                                                           name="postal_code"
                                                           placeholder="Código Postal"
                                                           :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.postal_code'),
                                                       'is-valid': (fields.$step1 && fields.$step1.postal_code.valid)
                                                   }"
                                                           v-model="register_form.form_data.user.postal_code"
                                                           v-validate="validators.step1.postal_code">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step1.postal_code')">{{ errors.first('step1.postal_code') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <select name="country"
                                                    :class="{
                                                         'col-sm-12 form-control select-rounded':true,
                                                         'text-danger': errors.has('step1.country'),
                                                         'is-valid': (fields.$step1 && fields.$step1.country.valid)
                                                     }"
                                                    v-model="register_form.form_data.user.country"
                                                    v-validate="validators.step1.country">
                                                <option value="">Seleccione su país</option>
                                                <option v-for="(country,index) in countries" :key="index"
                                                        :value="country.id">
                                                    {{country.name}}
                                                </option>
                                            </select>
                                            <span class="text-danger" v-show="errors.has('step1.country')">{{ errors.first('step1.country') }}</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text"
                                                   name="state"
                                                   placeholder="Estado"
                                                   :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.state'),
                                                       'is-valid': (fields.$step1 && fields.$step1.state.valid)
                                                   }"
                                                   v-model="register_form.form_data.user.state"
                                                   v-validate="validators.step1.state">
                                            <span class="text-danger" v-show="errors.has('step1.state')">{{ errors.first('step1.state') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text"
                                                   name="city"
                                                   placeholder="Ciudad"
                                                   :class="{
                                                       'col-sm-12 form-control':true,
                                                       'text-danger': errors.has('step1.city'),
                                                       'is-valid': (fields.$step1 && fields.$step1.city.valid)
                                                   }"
                                                   v-model="register_form.form_data.user.city"
                                                   v-validate="validators.step1.city">
                                            <span class="text-danger" v-show="errors.has('step1.city')">{{ errors.first('step1.city') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </tab-content>
                    <tab-content title="Contraseña" icon="" :before-change="stepValidator">
                        <form data-vv-scope="step2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>Configura tu contraseña</h2>
                                    <span>La contraseña debe contener al menos: 6 caracteres, 1 letra mayúscula, 1 letra minúscula y 1 número</span>
                                    <div class="col-sm-12 apithy-with-icon mt-3">
                                        <div class="row  padding-right-4p">
                                            <div class="input-group-prepend">
                                                    <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('step2.password'),
                                                       'is-valid': (fields.$step2 && fields.$step2.password.valid)
                                                        }">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                            </div>
                                            <div class="width-84">
                                                <input type="password"
                                                       name="password"
                                                       placeholder="Contraseña"
                                                       ref="password"
                                                       :class="{
                                               'col-sm-12 form-control':true,
                                               'text-danger': errors.has('step2.password'),
                                               'is-valid': (fields.$step2 && fields.$step2.password.valid)
                                           }"
                                                       v-model="register_form.form_data.user.password"
                                                       v-validate="validators.step2.pwd"
                                                       @input="passwordStrength">
                                            </div>
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
                                        <span class="text-danger" v-show="errors.has('step2.password')">{{ errors.first('step2.password') }}</span>
                                    </div>
                                    <br>
                                    <div class="col-sm-12 apithy-with-icon">
                                        <div class="row  padding-right-4p">
                                            <div class="input-group-prepend">
                                                    <span :class="{
                                                       'input-group-text icon':true,
                                                       'text-danger': errors.has('step2.confirm_password'),
                                                       'is-valid': (fields.$step2 && fields.$step2.confirm_password.valid)
                                                        }">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                            </div>
                                            <div class="width-84">
                                                <input type="password"
                                                       name="confirm_password"
                                                       placeholder="Confirmar contraseña"
                                                       :class="{
                                               'col-sm-12 form-control':true,
                                               'text-danger': errors.has('step2.confirm_password'),
                                               'is-valid': (fields.$step2 && fields.$step2.confirm_password.valid)
                                           }"
                                                       v-model="register_form.form_data.user.password_confirmation"
                                                       v-validate="validators.step2.pwd_confirmation">
                                            </div>
                                        </div>
                                        <span class="text-danger" v-show="errors.has('step2.confirm_password')">{{ errors.first('step2.confirm_password') }}</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </tab-content>
                    <tab-content title="Empresa" icon="" v-if="register_form.type.company"
                                 :before-change="stepValidator">
                        <form data-vv-scope="step3">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>Háblanos de tu empresa</h2>
                                    <div class="row justify-content-md-center">
                                        <div class="col-sm-12">
                                            <p>¿A que sector pertenece?</p>
                                            <select name="sector"
                                                    :class="{
                                                    'form-control select-rounded':true,
                                                    'text-danger': errors.has('step3.sector'),
                                                    'is-valid': (fields.$step3 && fields.$step3.sector.valid)
                                                }"
                                                    v-model="register_form.form_data.company.sector"
                                                    v-validate="validators.step3.sector">
                                                <option value="">Seleccione un sector</option>
                                                <option v-for="(sector, index) in sectors" :key="index" :value="sector">
                                                    {{sector}}
                                                </option>
                                            </select>
                                            <span class="text-danger" v-show="errors.has('step3.sector')">{{ errors.first('step3.sector') }}</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content-md-center">
                                        <div class="col-sm-12">
                                            <p>¿Que tamaño tiene tu empresa?</p>
                                            <select name="company_size"
                                                    :class="{
                                                    'form-control select-rounded':true,
                                                    'text-danger': errors.has('step3.company_size'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_size.valid)
                                                }"
                                                    v-model="register_form.form_data.company.size"
                                                    v-validate="validators.step3.size">
                                                <option value="">Selecione su tamaño de empresa</option>
                                                <option v-for="(size,index) in sizes" :value="size.value">{{ size.label
                                                    }}
                                                </option>
                                            </select>
                                            <span class="text-danger" v-show="errors.has('step3.size')">{{ errors.first('step3.size') }}</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row justify-content md-center">
                                        <div class="col-sm-12">
                                            <p>¿Qué dominio de correos utiliza la empresa? Solo los usuarios con estos
                                                correos podrán registrarse en su espacio de trabajo. Ejemplo (@miempresa.com)</p>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   name="valid_domain"
                                                   :class="{
                                                    'form-control col-sm-12':true,
                                                    'text-danger': errors.has('step3.valid_domain'),
                                                    'is-valid': (fields.$step3 && fields.$step3.valid_domain.valid)
                                                }"
                                                   placeholder="Dominio de correo electrónico"
                                                   v-model="register_form.new_domain"
                                                   v-validate="validators.step3.valid_domain">
                                            <span class="text-danger" v-show="errors.has('step3.valid_domain')">{{ errors.first('step3.valid_domain') }}</span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span class="col-sm-12 btn user-register-button-top"
                                                  @click="addEmailDomain">Añadir</span>
                                        </div>
                                    </div>
                                    <div class="row justify-content text-left container-domains">
                                        <div class="input-group mt-2 col-sm-4 domain-item"
                                             v-for="(item,index) in register_form.form_data.company.valid_domains">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text pre">@</span>
                                            </div>

                                            <span class="form-control mdl">
                                            {{ item.domain }}
                                        </span>

                                            <div class="input-group-append">
                                            <span class="input-group-text nxt" @click="removeEmailDomain(index)">
                                                x
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-5">
                                    <h2>Datos de contacto</h2>
                                    <div class="row justify-content-md-center apithy-with-icon mb-4">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                        'input-group-text icon':true,
                                                        'text-danger': errors.has('step3.company_name'),
                                                        'is-valid': (fields.$step3 && fields.$step3.company_name.valid)
                                                        }">
                                                        <i class="fas fa-briefcase"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="text"
                                                           name="company_name"
                                                           :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.company_name'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_name.valid)
                                               }"
                                                           placeholder="Nombre"
                                                           v-model="register_form.form_data.company.name"
                                                           v-validate="validators.step3.company_name">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step3.company_name')">{{ errors.first('step3.company_name') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                        'input-group-text icon':true,
                                                        'text-danger': errors.has('step3.company_website'),
                                                        'is-valid': (fields.$step3 && fields.$step3.company_website.valid)
                                                        }">
                                                        <i class="fas fa-globe"></i>
                                                    </span>
                                                </div>
                                                <div class="width-84">
                                                    <input type="text"
                                                           name="company_website"
                                                           :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.company_website'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_website.valid)
                                               }"
                                                           placeholder="Sitio web"
                                                           v-model="register_form.form_data.company.website"
                                                           v-validate="validators.step3.company_website">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step3.company_website')">{{ errors.first('step3.company_website') }}</span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-center mb-4">
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text"
                                                   name="r_social"
                                                   :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.r_social'),
                                                    'is-valid': (fields.$step3 && fields.$step3.r_social.valid)
                                               }"
                                                   placeholder="Razón social"
                                                   v-model="register_form.form_data.company.r_social"
                                                   v-validate="validators.step3.r_social">
                                            <span class="text-danger" v-show="errors.has('step3.r_social')">{{ errors.first('step3.r_social') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text" name="company_rfc"
                                                   :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.company_rfc'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_rfc.valid)
                                               }"
                                                   placeholder="RFC"
                                                   v-model="register_form.form_data.company.rfc"
                                                   v-validate="validators.step3.company_rfc">
                                            <span class="text-danger" v-show="errors.has('step3.company_rfc')">{{ errors.first('step3.company_rfc') }}</span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-md mb-4">
                                        <div class="col-sm-12 col-md-6 apithy-with-icon">
                                            <div class="row  padding-right-4p">
                                                <div class="input-group-prepend">
                                                    <span :class="{
                                                        'input-group-text icon':true,
                                                    'text-danger': errors.has('step3.company_postal_code'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_postal_code.valid)
                                                        }">
                                                        CP
                                                    </span>
                                                </div>
                                                <div class="width-83">
                                                    <input type="text"
                                                           name="company_postal_code"
                                                           :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.company_postal_code'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_postal_code.valid)
                                               }"
                                                           placeholder="Código postal"
                                                           v-validate="validators.step3.company_postal_code"
                                                           v-model="register_form.form_data.company.postal_code">
                                                </div>
                                            </div>
                                            <span class="text-danger" v-show="errors.has('step3.company_postal_code')">{{ errors.first('step3.company_postal_code') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <select name="company_country"
                                                    :class="{
                                                    'form-control select-rounded':true,
                                                    'text-danger': errors.has('step3.company_country'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_country.valid)
                                                }"
                                                    v-model="register_form.form_data.company.country"
                                                    v-validate="validators.step3.company_country">
                                                <option value="">Seleccione su país</option>
                                                <option v-for="(country,index) in countries" :key="index"
                                                        :value="country.id">
                                                    {{country.name}}
                                                </option>
                                            </select>
                                            <span class="text-danger" v-show="errors.has('step3.company_country')">{{ errors.first('step3.company_country') }}</span>
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-center">
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text"
                                                   name="company_state"
                                                   placeholder="Estado"
                                                   :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.company_state'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_state.valid)
                                                   }"
                                                   v-model="register_form.form_data.company.state"
                                                   v-validate="validators.step3.company_state">
                                            <span class="text-danger" v-show="errors.has('step3.company_state')">{{ errors.first('step3.company_state') }}</span>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <input type="text"
                                                   name="company_city"
                                                   placeholder="Ciudad"
                                                   :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step3.company_city'),
                                                    'is-valid': (fields.$step3 && fields.$step3.company_city.valid)
                                                   }"
                                                   v-model="register_form.form_data.company.city"
                                                   v-validate="validators.step3.company_city">
                                            <span class="text-danger" v-show="errors.has('step3.company_city')">{{ errors.first('step3.company_city') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </tab-content>
                    <tab-content title="Espacio" icon="" v-if="register_form.type.company"
                                 :before-change="stepValidator">
                        <form data-vv-scope="step4">
                            <div class="row justify-content-md-center">
                                <div class="col-sm-12">
                                    <h2>¿Que URL tendrá tu espacio de trabajo?</h2>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>Es la dirección que usarás para conectarte a Apithy</p>
                                            <input type="text"
                                                   name="domain"
                                                   :class="{
                                                    'col-sm-12 form-control':true,
                                                    'text-danger': errors.has('step4.domain'),
                                                    'is-valid': (fields.$step4 && fields.$step4.domain.valid)
                                               }"
                                                   placeholder="Nombre"
                                                   v-model="register_form.form_data.company.domain"
                                                   v-validate="validators.step4.domain">
                                            <span class="text-danger" v-show="errors.has('step4.domain')">{{ errors.first('step4.domain') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </tab-content>
                    <tab-content title="Condiciones" icon="">
                        <div class="row justify-content-md-center">
                            <div class="col-sm-12">
                                <h2>Revisa las condiciones</h2>
                                <p>Al elegir "Aceptar" comprendes y aceptas los <a target="_blank" href="/aviso-de-privacidad">Términos de Privacidad</a> y
                                    las
                                    <a target="_blank" href="/terminos-y-condiciones">Condiciones de Servicio a Cliente</a>.</p>
                            </div>
                        </div>
                    </tab-content>

                    <div slot="footer" slot-scope="props" class="row justify-content-md-center">
                        <div class="col-sm-10">
                            <div class="wizard-footer-left col-xs-6 back-btn">
                                <button v-if="props.activeTabIndex > 0" @click="prevStep(props)">
                                    <i class="fas fa-angle-left"></i> Regresar
                                </button>
                                <button v-else @click="showRegister"  >
                                    <i class="fas fa-angle-left"></i> Regresar
                                </button>
                            </div>
                            <div class="wizard-footer-right col-xs-6 continue-btn">
                                <button v-if="!props.isLastStep" :disabled="errors.any() ? true : false" :class="[errors.any() ? 'continue-btn-invalid' : 'continue-btn-valid']" @click="nextStep(props)">
                                    Continuar <i class="fas fa-angle-right"></i>
                                </button>

                                <wizard-button  v-else :disabled="errors.any()" class="continue-btn-valid" @click.native="finishRegister">{{props.isLastStep ? 'Aceptar' :
                                    'Continuar'}}
                                </wizard-button>
                            </div>
                        </div>
                    </div>
                </form-wizard>

                <div class="col-md-4 contenedor-persona-apithy">
                    <div class="row social-connect mt-3">
                        <h3 class="text-center mb-2">Social connect</h3>
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
                    </div>
                   <img v-bind:src="registerImage" class="persona-apithy">
                </div>

            </div>

            <div class="row justify-content-md-center register" v-if="register_email_resend.show">
                <div class="col-sm-12">
                    <img src="/images/resources/svg/estasAUnPaso.svg" alt="" class="img-responsive one-step">
                </div>
                <div class="col-sm-10 mt-3">
                    <h2>Est&aacute;s a un paso de ser parte de Apithy</h2>
                    <p>Hemos enviado un correo para validar tu direcci&oacute;n de correo <br>"{{ user.email }}". Da clic en el
                        enlace para continuar el proceso.</p>
                </div>
                <br>
                <div class="col-sm-12 text-center">
                    <a v-bind:href="user.confirmation_url" class="user-register-button-top col-sm-3">Volver a enviar</a>
                </div>
                <br><br>
                <div class="col-sm-12 text-center">
                    <a href="/login" class="user-register-button-top col-sm-3">Este no es mi correo</a>
                </div>
            </div>

            <div class="row register" v-if="register_welcome.show">
                <div class="col-sm-12">
                    <h2>¡Bienvenido a Apithy!</h2>
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

    const dictionary = {
        es: {
            attributes: {
                firstname: 'nombre',
                lastname: 'apellido',
                email: 'correo electrónico',
                confirm_email: "correo electrónico",
                phone: 'teléfono',
                country: 'país',
                state: 'estado',
                postal_code: 'código postal',
                city: 'ciudad',
                pwd: 'contraseña',
                pwd_confirmation: 'contraseña',
                confirm_password:'confirmar contraseña',
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
            }
        }
    };

    export default {
        name: "ApithyRegister",
        components: {
            FormWizard,
            TabContent,
            WizardButton,
        },
        props: [
            'countries_data'
        ],
        computed:{
            registerImage(){
                return '/images/resources/png/registro'+this.current_step+'.png';
            },
            terminos_y_condiciones() {
                return route('terminos-y-condiciones').toString();
            }
        },
        mounted() {
            VeeValidate.Validator.localize('es', es)
            VeeValidate.Validator.localize(dictionary);

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
        methods: {
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
                        let token_field = document.getElementsByName('_token')[0];
                        this.register_form.form_data.token = {name: token_field.name, value: token_field.value};
                        break;
                    case 'email_resend':
                        this.register_type.show = false;
                        this.register_form.show = false;
                        this.register_email_resend.show = true;
                        this.register_welcome.show = false;
                        break;
                    case 'welcome':
                        this.register_type.show = false;
                        this.register_form.show = false;
                        this.register_email_resend.show = false;
                        this.register_welcome.show = true;
                        break;
                    default:
                        this.register_type.show = true;
                        this.register_form.show = false;
                        this.register_email_resend.show = false;
                        this.register_welcome.show = false;
                        break;
                }

                if(this.current_step>1){
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
                if (this.$validator.validate()) {
                    this.prepareData();
                } else {
                    console.log(this.errors)
                }
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
                    company_id: '',
                    area_id: '',
                    position_id: '',
                    register_type: "on_site",
                    role_id: '',
                    activated: 0
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

                if (this.register_form.type.company) {

                    user_params.company_info = company_params;
                    user_params.role_id = 9;
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
                    this.user.confirmation_url = "/register/confirm/resend/" + this.user.confirmation_code;
                    this.showRegister('email_resend');
                }).catch((error) => {
                    let errors_list = error.response.data.errors;

                    if (Array.isArray(errors_list)) {
                        this.server_errors.data = errors_list;
                    } else {
                        this.server_errors.data = new Array(errors_list);
                    }

                    this.server_errors.show = true;


                });
            },
        },
        data() {
            return {
                user: [],
                current_step:1,
                server_errors: {
                    show: false,
                    data: []
                },
                register_email_resend: {
                    show: false
                },
                password_strength: 0,
                register_form: {
                    show: false,
                    type: {
                        company: false,
                        user: false
                    },
                    new_domain: '',
                    form_data: {
                        token: {
                            label: '',
                            value: ''
                        },
                        user: {
                            firstname: '',
                            lastname: '',
                            email: '',
                            phone: '',
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
                    show: true,
                    company_domain: '',
                    dont_exist: false
                },
                register_welcome: {
                    show: false
                },
                countries: this.countries_data,
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
                validators: {
                    step1: {
                        firstname: {
                            required: true,
                            alpha_spaces: true,
                            min: 2
                        },
                        lastname: {
                            required: true,
                            alpha_spaces: true,
                            min: 2
                        },
                        email: {
                            required: true,
                            email: true,
                            verify_email_exist:true,
                            regex:'^[a-z0-9_+]([a-z0-9_+.]+[a-z0-9_+])?\\@[a-z0-9.-]+$'
                        },
                        email_confirmation: {
                            required: true,
                            email: true,
                            confirmed: 'email'
                        },
                        phone: {
                            required: true,
                            min: 10,
                            regex: '^((\\+[0-9]{1,3})(-|)|([0-9]{1,3})(-|)|)([0-9]{3,4})(-|)([0-9]{2,3})(-|)([0-9]{2,4})(-|)(([0-9]{2}))$'
                        },
                        country: {
                            required: true
                        },
                        state: {
                            required: true,
                            alpha_spaces: true
                        },
                        postal_code: {
                            required: true,
                            numeric: true,
                            length: 5
                        },
                        city: {
                            required: true,
                            alpha_spaces: true
                        }
                    },
                    step2: {
                        pwd: {
                            required: true,
                            min: 6,
                            verify_password: true
                        },
                        pwd_confirmation: {
                            required: true,
                            confirmed: 'password'
                        }
                    },
                    step3: {
                        sector: {
                            required: true
                        },
                        size: {
                            required: true
                        },
                        company_name: {
                            required: true,
                            verify_company_name: true,
                            alpha_spaces:true
                        },
                        company_website: {
                            required: true,
                            url: true
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
                        company_postal_code: {
                            required: true,
                            numeric: true,
                            length: 5
                        },
                        valid_domain: {
                            regex: '^(?!:\\/\\/)([a-zA-Z0-9-]+\\.){0,5}[a-zA-Z0-9-][a-zA-Z0-9-]+\\.[a-zA-Z]{2,64}?$',
                        },
                        city: {
                            required: true,
                            alpha_spaces: true
                        }
                    },
                    step4: {
                        workspace: {
                            required: true,
                            alpha_dash: true,

                        },
                        domain: {
                            required: true,
                            alpha: true,
                            verify_account_name: true
                        }
                    }
                }
            };
        }
    }
</script>

<style scoped>
    .width-83 {
        width: 83%;
    }

    .width-84 {
        width: 84%;
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

    .apithy-with-icon span.icon {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom-left-radius: 10px !important;
        border-top-left-radius: 10px !important;
        background-color: transparent !important;
        border: solid 1px #B3B3B3;
        border-right: none !important;
    }

    .apithy-with-icon input {
        margin-top: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: none !important;
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

    .continue-btn-valid{
        color: #FFA81E !important;
    }

    .continue-btn-invalid{
        color: #B2B2B2 !important;
    }

    .continue-btn > button {
        font-size: 1.2rem !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        cursor: pointer;
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
        border-radius: 40px;
        border: none;
        font-family: 'AvenirRoman', sans-serif;
        box-shadow: 2px 2px 6px 0 rgba(88, 88, 88, 0.41);
        border-top-left-radius: 40px !important;
        border-bottom-left-radius: 40px !important;
        font-size: .8rem;
        padding: 10px 10px 10px 10px;
        margin-top: 30px;
    }

    .company-space-join {
        margin-top: 50px;
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
        border-radius: 10px !important;
        border: solid 1px lightgrey;
    }

    input.text-danger {
        border: solid 1px red !important;
    }

    span.text-danger{
        color: red;
        font-size: 0.9rem;
        margin-left: 6px;
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

    .password-strength{
        width: 91% !important;
        margin-left: 4px !important;
    }

    @media all and (max-width: 641px) {
        .stepTitle {
            display: none;
        }

        .password-strength{
            width: 90% !important;
            margin-left: 2px !important;
        }
    }

</style>
