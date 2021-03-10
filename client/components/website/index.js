export default {
    'apithy-register':  () => import('./ApithyRegister'),
    'apithy-fast-register': () => import('./FastRegister'),
    'apithy-confirm-code': () => import('./ApithyConfirmCode'),
    'apithy-company-user-register': () => import('./CompanyFastRegister'),
    'apithy-company-user-invitation-register': () => import('./CompanyUserRegister'),
    'recovery-password': () => import('./reset-password/RecoveryPassword'),
    'reset-password': import('./reset-password/ResetPassword'),
    'apithy-landingpage-navbar':import('./LandingPageNavbar'),
    'apithy-landingpage': import('./LandingPage'),
    'apithy-landingpage-two': import('./LandingPage2'),
    'apithy-homepage':import('./HomePage'),
};
