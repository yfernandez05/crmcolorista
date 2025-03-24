<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="user-pro">
                    <a class="has-arrow waves-effect waves-dark d-flex no-block" href="javascript:void(0)" aria-expanded="false">
                        <img src="{{asset('images/users/1.jpg')}}" alt="user-img" class="img-circle">
                        <span class="hide-menu text-truncate ">{{ Auth::user()->name }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <router-link :to="{name: 'spa'}"><i class="ti-user"></i> Perfil</router-link>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                document.getElementById('logout-form-menu').submit();">
                                <i class="fa fa-power-off"></i> {{ __('Logout') }}
                            </a>
                            <!-- text-->
                            <form id="logout-form-menu" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                {{-- @if (Auth::user()->idrol == Rule::PERSONAL_ATTENDANCE_ACCESS || Auth::user()->idrol == Rule::PERSONAL_STAND_ACCESS || Auth::user()->idrol == Rule::PERSONAL_STAND_SALE_ACCESS || in_array(Auth::user()->idrol, Rule::ADMINISTRATORS_ACCESS)) --}}
                @if (in_array(Auth::user()->rol_id, Rule::ADMINISTRATORS_ACCESS))
                <div class="dropdown-divider"></div>
                <li>
                    <router-link class="waves-effect waves-dark" :to="{ name: 'spa.lectorqr'}" aria-expanded="false">
                        <i class="fas fa-qrcode"></i>
                        <span class="hide-menu">Lector QR</span>
                    </router-link>
                </li>
                <div class="dropdown-divider"></div>
                @endif


                {{-- @if (Auth::user()->idrol == Rule::SYSTEM_ADMIN_ROLE && Auth::user()->idcuenta == Rule::SYSTEM_COMPANY_ACCOUNT) --}}
                @if (Auth::user()->rol_id == Rule::SYSTEM_ADMIN_ROLE)
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="ti-panel"></i>
                        <span class="hide-menu">Sistema</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!-- <li><router-link :to="{name: 'spa.cuenta'}">Cuentas</router-link></li> -->
                        <!-- <li><router-link :to="{name: 'spa.administrarcliente'}">Administrar Cliente</router-link></li> -->
                        <li><router-link :to="{name: 'spa.logerror'}">Log Error</router-link></li>
                    </ul>
                </li>
                @endif

                @if (in_array(Auth::user()->rol_id, Rule::ADMINISTRATORS_ACCESS))
                <li class="nav-small-cap">
                    <span>ADMINISTRADOR</span>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-user-lock"></i>
                        <span class="hide-menu">Administrador</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><router-link :to="{name: 'spa.rol'}">Roles</router-link></li>
                        <li><router-link :to="{name: 'spa.user'}">Usuarios</router-link></li>

                        <li><router-link :to="{name: 'spa.tipocomprobante'}">Tipo Comprobante</router-link></li>
                        <li><router-link :to="{name: 'spa.tipoAtencion'}">Tipo Atencion</router-link></li>
                        <li><router-link :to="{name: 'spa.etiquetaTelefonica'}">Etiqueta Telefonica</router-link></li>
                        <!-- <li><router-link :to="{name: 'spa.campania'}">Campañas</router-link></li>
                        <li><router-link :to="{name: 'spa.evento'}">Stands</router-link></li>
                        <li><router-link :to="{name: 'spa.tipoAtencion'}">Tipo de atención</router-link></li>
                        <li><router-link :to="{name: 'spa.buttonmessage'}">Botones directos</router-link></li> -->
                    </ul>
                </li>
                @endif

                @if (in_array(Auth::user()->rol_id, Rule::ADMINISTRATORS_ACCESS) || in_array(Auth::user()->rol_id, Rule::DOCENTE_ACCESS))
                <li class="nav-small-cap">
                    <span>ASISTENCIA</span>
                </li>

                @if (in_array(Auth::user()->rol_id, Rule::ADMINISTRATORS_ACCESS))
                <li>
                    <router-link class="waves-effect waves-dark" :to="{name: 'spa.asistencia'}" aria-expanded="false">
                        {{-- <i class="ti-server"></i> --}}
                        <i class="far fa-check-square"></i>
                        <span class="hide-menu">Asistencia</span>
                    </router-link>
                </li>
                @endif

                <li>
                    <router-link class="waves-effect waves-dark" :to="{name: 'spa.asistenciadocente'}" aria-expanded="false">
                        {{-- <i class="ti-server"></i> --}}
                        <i class="far fa-check-square"></i>
                        <span class="hide-menu">Asistencia Docente</span>
                    </router-link>
                </li> 
                @endif

                @if (in_array(Auth::user()->rol_id, Rule::ADMINISTRATORS_ACCESS))
                <li class="nav-small-cap">
                    <span>PROFESIONAL</span>
                </li>
                {{-- <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-user-lock"></i>
                        <span class="hide-menu">DOCENTES</span>
                    </a>
                </li> --}}
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-user-lock"></i>
                        <span class="hide-menu">Estudiantes</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><router-link :to="{name: 'spa.alumno'}">Alumnos</router-link></li>

                    </ul>
                </li>
                <li>
                    <router-link class="waves-effect waves-dark" :to="{ name: 'spa.curso'}" aria-expanded="false">
                        <i class="fas fa-book"></i>
                        <span class="hide-menu">Curso</span>
                    </router-link>
                </li>
                <li>
                    <router-link class="waves-effect waves-dark" :to="{ name: 'spa.docente'}" aria-expanded="false">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="hide-menu">Cursos de Docentes</span>
                    </router-link>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-user-lock"></i>
                        <span class="hide-menu">Carreras</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><router-link :to="{name: 'spa.carrera'}">Carreras</router-link></li>
                        <li><router-link :to="{name: 'spa.aula'}">Aulas</router-link></li>
                        <li><router-link :to="{name: 'spa.planestudio'}">Plan de estudio</router-link></li>
                    </ul>
                </li>

                <li class="nav-small-cap">
                    <span>FORMULARIO</span>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-user-lock"></i>
                        <span class="hide-menu">Matriculas</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><router-link :to="{name: 'spa.matricula'}">Matricula</router-link></li>
                        <li><router-link :to="{name: 'spa.ciclo'}">Modulo</router-link></li>
                        <li><router-link :to="{name: 'spa.turno'}">Turno</router-link></li>
                        {{-- <li><router-link :to="{name: 'spa.condicion'}">Condiciones</router-link></li> --}}
                        {{-- <li><router-link :to="{name: 'spa.inscripcion'}">Inscripción</router-link></li> --}}

                        {{-- <li><router-link :to="{name: 'spa.periodo'}">Periodo</router-link></li> --}}
                    </ul>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-users"></i>
                        <span class="hide-menu">Prospecto</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><router-link :to="{name: 'spa.import'}">Importar Prospecto</router-link></li>
                        <li><router-link :to="{name: 'spa.prospecto'}">Prospecto</router-link></li>
                        <li><router-link :to="{name: 'spa.atencion'}">Seguimientos</router-link></li>

                    </ul>
                </li>

                <li class="nav-small-cap">
                    <span>CAJA</span>
                </li>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false">
                        <i class="fas fa-cash-register"></i>
                        <span class="hide-menu">Pagos</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!-- <li><router-link :to="{name: 'spa.carrera'}">Matricula</router-link></li>-->
                        <li><router-link :to="{name: 'spa.conceptopago'}">Concepto de pago</router-link></li>
                        <li><router-link :to="{name: 'spa.pago'}">Pago</router-link></li>
                        <li><router-link :to="{name: 'spa.contrato'}">Contrato</router-link></li>
                    </ul>
                </li>


                <!-- <li>
                    <router-link class="waves-effect waves-dark" :to="{name: 'spa.cliente'}" aria-expanded="false">
                        <i class="fas fa-user-edit"></i>
                        <span class="hide-menu">Registros</span>
                    </router-link>
                </li>
                <li>
                    <router-link class="waves-effect waves-dark" :to="{name: 'spa.reporte'}" aria-expanded="false">
                        <i class="fas fa-chart-pie"></i>
                        <span class="hide-menu">Reporte</span>
                    </router-link>
                </li> -->
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
