import Profile from '../Profile.vue';
import ProfileForm from '../ProfileForm.vue';
import Register from '../Register.vue';
import Security from '../Security.vue';
import Invitations from './Invitations.vue';
import AppCreate from './AppCreate.vue';
import ImageUpload from '../ImageUpload';
import SessionInfoForm from './SessionInfoFom';
import ExperienceShowForm from './ExperienceAdminShowFom';
import ExperienceEnrollmentList from './ExperienceEnrollmentsList';
import AdventuresTree from './AdventuresTree';
import StudentAnalytics from '../platform/StudentAnalytics';
import PaymentInformation from '../PaymentInformation';
import PaymentInformationForm from '../PaymentInformationForm';
import UserForm from './user-form/CreateForm';

import AdminNav from './AdminNav';

import Select from '../AreasPositionsSelector';
import Asignator from './EntityAsignator'
import ApithyChart from './ApithyChart'

import AreaPositionForm from './company-forms/AreasPositionsForm';
import TagList from './tags/TagList';
import AbilityList from './abilities/AbilityList';

import ApithyTable from './ApithyTable';

import DemoInvitations from './users/DemoInvitations';

// Users components
import UsersFilter from './users/UsersFilter';
import UsersList from './users/UserList';
import UsersForm from './users/UserForm';
import UsersImport from './users/UsersImport';
import UsersInvitations from './users/UsersInvitations';
import UsersEdit from './users/UsersEdit';

// Companies components
import CompaniesList from './company/CompanyList';
import CompaniesFilter from './company/CompanyFilter';
import CompanyForm from './company/CompanyForm';
import CompanyEdit from './company/CompanyEdit';
import CompanyMenu from  './company/CompanyMenu';
import CompanyBudgetForm from '../admin/company/CompanyBudgetForm';
import CompanyBudgetList from './company/CompanyBudgetList';

// Experiences components
import ExperienceMenu from './experiences/ExperiencesMenu';
import ExperienceForm from './experiences/ExperiencesForm';
import ExperienceCarousel from './experiences/ExperienceCarousel';

// Experience editor
import ExperienceEditor from './experiences/editor/ExperienceEditor';

// Sessions components
import SessionList from './sessions/SessionsList';
import SessionsForm from './sessions/SessionForm';

// Dashboard
/*import MenuDashboard from './dashboard/Menu';
import DetailedStackedGraph from "./dashboard/detailed/DetailedStackedGraph";
import DetailedAverageGraph from "./dashboard/detailed/DetailedAverageGraph";
import DetailedLicensesGraph from "./dashboard/detailed/DetailedLicensesGraph";
import DetailedPopularityGraph from "./dashboard/detailed/DetailedPopularityGraph";
import DetailedCalificationsGraph from "./dashboard/detailed/DetailedCalificationsGraph";
import GeneralDashboard from './dashboard/admin/AdminGeneralDashboard';*/

// Root
/*
import RootSummaryDashboard from './dashboard/root/SummaryDashboard';
import RootJourneysDashboard from './dashboard/root/JourneysDashboard';
import RootExperienceDashboard from './dashboard/root/ExperienceDashboard';
import RootUsersDashboard from './dashboard/root/UsersDashboard';
*/

export default {
    'apithy-profile': Profile,
    'apithy-profile-form': ProfileForm,
    'apithy-register': Register,
    'apithy-security': Security,
    'apithy-app-create': AppCreate,
    'apithy-invitations': Invitations,
    'apithy-image-upload': ImageUpload,
    'apithy-select': Select,
    'apithy-entity-asignator': Asignator,
    'apithy-experiences-show-form': ExperienceShowForm,
    'apithy-experience-enrollment-list': ExperienceEnrollmentList,
    'apithy-tree': AdventuresTree,
    'apithy-chart': ApithyChart,
    'apithy-student-analytics': StudentAnalytics,
    'apithy-user-payment-information': PaymentInformation,
    'apithy-user-payment-information-form': PaymentInformationForm,
    'apithy-areaposition-form': AreaPositionForm,
    'apithy-user-form': UserForm,
    'apithy-ability-list': AbilityList,
    'apithy-tag-list': TagList,

    'apithy-admin-nav': AdminNav,
    'apithy-table': ApithyTable,

    'apithy-users-filter': UsersFilter,
    'apithy-users-list': UsersList,
    'apithy-users-form': UsersForm,
    'apithy-users-import': UsersImport,
    'apithy-users-invitations': UsersInvitations,
    'apithy-users-edit': UsersEdit,

    'apithy-companies-list': CompaniesList,
    'apithy-companies-filter': CompaniesFilter,
    'apithy-companies-form': CompanyForm,
    'apithy-companies-edit': CompanyEdit,
    'apithy-company-menu': CompanyMenu,
    'apithy-budget-list': CompanyBudgetList,
    'apithy-companies-budget-form': CompanyBudgetForm,

    'apithy-experiences-menu': ExperienceMenu,
    'apithy-experiences-form': ExperienceForm,
    'apithy-experiences-carousel': ExperienceCarousel,
    'apithy-experience-editor': ExperienceEditor,

    'apithy-sessions-list': SessionList,
    'apithy-sessions-info-form': SessionInfoForm,
    'apithy-sessions-form': SessionsForm,

    'apithy-demo-invitations': DemoInvitations,

    /*'apithy-menu-dashboard': MenuDashboard,
    'apithy-root-summary-dashboard': RootSummaryDashboard,
    'apithy-root-journeys-dashboard': RootJourneysDashboard,
    'apithy-root-experiences-dashboard': RootExperienceDashboard,
    'apithy-root-users-dashboard': RootUsersDashboard,
    'apithy-admin-general-dashboard': GeneralDashboard,
    'apithy-admin-detail-stacked': DetailedStackedGraph,
    'apithy-admin-detail-average': DetailedAverageGraph,
    'apithy-admin-detail-licenses': DetailedLicensesGraph,
    'apithy-admin-detail-popularity': DetailedPopularityGraph,
    'apithy-admin-detail-califications': DetailedCalificationsGraph,*/
};