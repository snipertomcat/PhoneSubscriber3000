// registration
export default {
  'coming-soon': () => import('./ComingSoon'),
  'main-page': () => import('./MainPage'),
  'apithy-experience-editor': () => import('../admin/experiences/editor/ExperienceEditor'),
  'apithy-navbar': () => import('../navbar/Navbar'),
}
