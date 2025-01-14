import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter)

window.appRootUrl = window.location.origin;
window.appApiUrl = `${window.appRootUrl}/rest`;
window.intranetBaseUrl = '/crm';

export default new VueRouter({
    mode: 'history',
    routes:[
        {
            path: intranetBaseUrl,
            name: 'spa',
            component: require('./components/empresa/EmpresaIndex').default
        },
        {
            path: `${intranetBaseUrl}/dashboard`,
            name: 'spa.dashboard',
            component: require('./components/dashboard/DashboardIndex').default
        },



        {
            path: `${intranetBaseUrl}/user`,
            name: 'spa.user',
            component: require('./components/user/UserIndex').default
        },
        {
            path: `${intranetBaseUrl}/user/registrar`,
            name: 'spa.user.registrar',
            component: require('./components/user/UserCreate').default
        },
        {
            path: `${intranetBaseUrl}/user/editar/:id`,
            name: 'spa.user.editar',
            component: require('./components/user/UserEdit').default
        },




        {
            path: `${intranetBaseUrl}/rol`,
            name: 'spa.rol',
            component: require('./components/rol/RolIndex').default
        },
        {
            path: `${intranetBaseUrl}/rol/registrar`,
            name: 'spa.rol.registrar',
            component: require('./components/rol/RolCreate').default
        },
        {
            path: `${intranetBaseUrl}/rol/editar/:id`,
            name: 'spa.rol.editar',
            component: require('./components/rol/RolEdit').default
        },



        {
            path: `${intranetBaseUrl}/condicion`,
            name: 'spa.condicion',
            component: require('./components/condicion/CondicionIndex').default
        },
        {
            path: `${intranetBaseUrl}/condicion/registrar`,
            name: 'spa.condicion.registrar',
            component: require('./components/condicion/CondicionCreate').default
        },
        {
            path: `${intranetBaseUrl}/condicion/editar/:id`,
            name: 'spa.condicion.editar',
            component: require('./components/condicion/CondicionEdit').default
        },



        {
            path: `${intranetBaseUrl}/alumno`,
            name: 'spa.alumno',
            component: require('./components/alumno/AlumnoIndex').default
        },
        {
            path: `${intranetBaseUrl}/alumno/registrar`,
            name: 'spa.alumno.registrar',
            component: require('./components/alumno/AlumnoCreate').default
        },
        {
            path: `${intranetBaseUrl}/alumno/editar/:id`,
            name: 'spa.alumno.editar',
            component: require('./components/alumno/AlumnoEdit').default
        },



        {
            path: `${intranetBaseUrl}/turno`,
            name: 'spa.turno',
            component: require('./components/turno/TurnoIndex').default
        },
        {
            path: `${intranetBaseUrl}/turno/registrar`,
            name: 'spa.turno.registrar',
            component: require('./components/turno/TurnoCreate').default
        },
        {
            path: `${intranetBaseUrl}/turno/editar/:id`,
            name: 'spa.turno.editar',
            component: require('./components/turno/TurnoEdit').default
        },



        {
            path: `${intranetBaseUrl}/curso`,
            name: 'spa.curso',
            component: require('./components/curso/CursoIndex').default
        },
        {
            path: `${intranetBaseUrl}/curso/registrar`,
            name: 'spa.curso.registrar',
            component: require('./components/curso/CursoCreate').default
        },
        {
            path: `${intranetBaseUrl}/curso/editar/:id`,
            name: 'spa.curso.editar',
            component: require('./components/curso/CursoEdit').default
        },



        {
            path: `${intranetBaseUrl}/carrera`,
            name: 'spa.carrera',
            component: require('./components/carrera/CarreraIndex').default
        },
        {
            path: `${intranetBaseUrl}/carrera/registrar`,
            name: 'spa.carrera.registrar',
            component: require('./components/carrera/CarreraCreate').default
        },
        {
            path: `${intranetBaseUrl}/carrera/editar/:id`,
            name: 'spa.carrera.editar',
            component: require('./components/carrera/CarreraEdit').default
        },



        {
            path: `${intranetBaseUrl}/ciclo`,
            name: 'spa.ciclo',
            component: require('./components/ciclo/CicloIndex').default
        },
        {
            path: `${intranetBaseUrl}/ciclo/registrar`,
            name: 'spa.ciclo.registrar',
            component: require('./components/ciclo/CicloCreate').default
        },
        {
            path: `${intranetBaseUrl}/ciclo/editar/:id`,
            name: 'spa.ciclo.editar',
            component: require('./components/ciclo/CicloEdit').default
        },



        {
            path: `${intranetBaseUrl}/planestudio`,
            name: 'spa.planestudio',
            component: require('./components/planestudio/PlanEstudioIndex').default
        },
        {
            path: `${intranetBaseUrl}/planestudio/registrar`,
            name: 'spa.planestudio.registrar',
            component: require('./components/planestudio/PlanEstudioCreate').default
        },
        {
            path: `${intranetBaseUrl}/planestudio/editar/:id`,
            name: 'spa.planestudio.editar',
            component: require('./components/planestudio/PlanEstudioEdit').default
        },



        {
            path: `${intranetBaseUrl}/conceptopago`,
            name: 'spa.conceptopago',
            component: require('./components/conceptopago/ConceptoIndex').default
        },
        {
            path: `${intranetBaseUrl}/conceptopago/registrar`,
            name: 'spa.conceptopago.registrar',
            component: require('./components/conceptopago/ConceptoCreate').default
        },
        {
            path: `${intranetBaseUrl}/conceptopago/editar/:id`,
            name: 'spa.conceptopago.editar',
            component: require('./components/conceptopago/ConceptoEdit').default
        },



        {
            path: `${intranetBaseUrl}/periodo`,
            name: 'spa.periodo',
            component: require('./components/periodo/PeriodoIndex').default
        },
        {
            path: `${intranetBaseUrl}/periodo/registrar`,
            name: 'spa.periodo.registrar',
            component: require('./components/periodo/PeriodoCreate').default
        },
        {
            path: `${intranetBaseUrl}/periodo/editar/:id`,
            name: 'spa.periodo.editar',
            component: require('./components/periodo/PeriodoEdit').default
        },



        {
            path: `${intranetBaseUrl}/inscripcion`,
            name: 'spa.inscripcion',
            component: require('./components/inscripcion/InscripcionIndex').default
        },
        {
            path: `${intranetBaseUrl}/inscripcion/registrar`,
            name: 'spa.inscripcion.registrar',
            component: require('./components/inscripcion/InscripcionCreate').default
        },
        {
            path: `${intranetBaseUrl}/inscripcion/editar/:id`,
            name: 'spa.inscripcion.editar',
            component: require('./components/inscripcion/InscripcionEdit').default
        },



        {
            path: `${intranetBaseUrl}/matricula`,
            name: 'spa.matricula',
            component: require('./components/matricula/MatriculaIndex').default
        },
        {
            path: `${intranetBaseUrl}/matricula/registrar`,
            name: 'spa.matricula.registrar',
            component: require('./components/matricula/MatriculaCreate').default
        },
        {
            path: `${intranetBaseUrl}/matricula/editar/:id`,
            name: 'spa.matricula.editar',
            component: require('./components/matricula/MatriculaEdit').default
        },











        





        {
            path: `${intranetBaseUrl}/cuenta`,
            name: 'spa.cuenta',
            component: require('./components/cuenta/CuentaIndex').default
        },
        {
            path: `${intranetBaseUrl}/cuenta/registrar`,
            name: 'spa.cuenta.registrar',
            component: require('./components/cuenta/CuentaCreate').default
        },
        {
            path: `${intranetBaseUrl}/cuenta/editar/:id`,
            name: 'spa.cuenta.editar',
            component: require('./components/cuenta/CuentaEdit').default
        },
        {
            path: `${intranetBaseUrl}/campania`,
            name: 'spa.campania',
            component: require('./components/campania/CampaniaIndex').default
        },
        {
            path: `${intranetBaseUrl}/campania/registrar`,
            name: 'spa.campania.registrar',
            component: require('./components/campania/CampaniaCreate').default
        },
        {
            path: `${intranetBaseUrl}/campania/editar/:id`,
            name: 'spa.campania.editar',
            component: require('./components/campania/CampaniaEdit').default
        },
        {
            path: `${intranetBaseUrl}/evento`,
            name: 'spa.evento',
            component: require('./components/evento/Eventoindex').default
        },
        {
            path: `${intranetBaseUrl}/evento/registrar`,
            name: 'spa.evento.registrar',
            component: require('./components/evento/EventoCreate').default
        },
        {
            path: `${intranetBaseUrl}/evento/editar/:id`,
            name: 'spa.evento.editar',
            component: require('./components/evento/EventoEdit').default
        },


        {
            path: `${intranetBaseUrl}/cliente`,
            name: 'spa.cliente',
            component: require('./components/cliente/ClienteIndex').default
        },
        {
            path: `${intranetBaseUrl}/clienteevento`,
            name: 'spa.clienteevento',
            component: require('./components/cliente/ClienteEventoIndex').default
        },


        {
            path: `${intranetBaseUrl}/tipoAtencion`,
            name: 'spa.tipoAtencion',
            component: require('./components/tipoAtencion/TipoAtencionIndex').default
        },
        {
            path: `${intranetBaseUrl}/tipoAtencion/registrar`,
            name: 'spa.tipoAtencion.registrar',
            component: require('./components/tipoAtencion/TipoAtencionCreate').default
        },
        {
            path: `${intranetBaseUrl}/tipoAtencion/editar/:id`,
            name: 'spa.tipoAtencion.editar',
            component: require('./components/tipoAtencion/TipoAtencionEdit').default
        },

        {
            path: `${intranetBaseUrl}/atencion/atender/:id`,
            name: 'spa.atencion.atender',
            component: require('./components/atencion/AtencionCreate').default
        },


        {
            path: `${intranetBaseUrl}/export`,
            name: 'spa.export',
            component: require('./components/basedatos/ExportBDIndex').default
        },
        {
            path: `${intranetBaseUrl}/import`,
            name: 'spa.import',
            component: require('./components/basedatos/ImportBDIndex').default
        },
        {
            path: `${intranetBaseUrl}/administrarcliente`,
            name: 'spa.administrarcliente',
            component: require('./components/administrarcliente/ClienteIndex').default
        },
        {
            path: `${intranetBaseUrl}/administrarcliente/editar/:id`,
            name: 'spa.administrarcliente.editar',
            component: require('./components/administrarcliente/ClienteEdit').default
        },
        {
            path: `${intranetBaseUrl}/logerror`,
            name: 'spa.logerror',
            component: require('./components/logerror/LogErrorIndex').default
        },


        {
            path: `${intranetBaseUrl}/reporte`,
            name: 'spa.reporte',
            component: require('./components/reporte/ReporteIndex').default
        },


        {
            path: `${intranetBaseUrl}/lectorqr`,
            name: 'spa.lectorqr',
            component: require('./components/lectorqr/LectorQR').default
        },


        {
            path: `${intranetBaseUrl}/buttonmessage`,
            name: 'spa.buttonmessage',
            component: require('./components/buttonmessage/ButtonMessageIndex').default
        },
        {
            path: `${intranetBaseUrl}/buttonmessage/registrar`,
            name: 'spa.buttonmessage.registrar',
            component: require('./components/buttonmessage/ButtonMessageCreate').default
        },
        {
            path: `${intranetBaseUrl}/buttonmessage/editar/:id`,
            name: 'spa.buttonmessage.editar',
            component: require('./components/buttonmessage/ButtonMessageEdit').default
        },

        {
            path: `${intranetBaseUrl}/asistencia`,
            name: 'spa.asistencia',
            component: require('./components/asistencia/AlumnoAsistencia').default
        },

        {
            path: `${intranetBaseUrl}/prospecto`,
            name: 'spa.prospecto',
            component: require('./components/prospecto/ProspectoIndex').default
        },
        {
            path: `${intranetBaseUrl}/prospecto/registrar`,
            name: 'spa.prospecto.registrar',
            component: require('./components/prospecto/ProspectoCreate').default
        },
        {
            path: `${intranetBaseUrl}/prospecto/editar/:id`,
            name: 'spa.prospecto.editar',
            component: require('./components/prospecto/ProspectoEdit').default
        },
        {
            path: `${intranetBaseUrl}/seguimiento`,
            name: 'spa.seguimiento',
            component: require('./components/seguimiento/SeguimientoIndex').default
        },
        {
            path: `${intranetBaseUrl}/seguimiento/registrar`,
            name: 'spa.seguimiento.registrar',
            component: require('./components/seguimiento/SeguimientoCreate').default
        },
        {
            path: `${intranetBaseUrl}/seguimiento/editar/:id`,
            name: 'spa.seguimiento.editar',
            component: require('./components/seguimiento/SeguimientoEdit').default
        },

        { path: '*', redirect: intranetBaseUrl },
        { path: '/', redirect: intranetBaseUrl },
    ]
  });
