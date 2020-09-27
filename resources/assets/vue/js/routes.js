import NotFound from './components/NotFound';

let Home = ()=> import(/* webpackChunkName: "Admin-Home"*/ './components/Admin/Home');
let CreateEvent = ()=> import(/* webpackChunkName: "Admin-CreateEvent"*/ './components/Admin/Management/CreateEvent');
let EventDetails = ()=> import(/* webpackChunkName: "Admin-EventDetails"*/ './components/Admin/Management/EventDetails');
let Eventlistings = ()=> import(/* webpackChunkName: "Admin-Eventlistings" */ './components/Admin/Management/EventListings');

export default {
    mode : 'history',
    routes : [
        {
          path: '*',
          component: NotFound
        },
        {
            name : 'Admin-Home',
            path : '/admin',
            component : Home
        },
        {
            name : 'Admin-Eventlistings',
            path : '/admin/events/manage',
            component: Eventlistings
        },
        {
            name : 'Admin-CreateEvent',
            path : '/admin/events/new',
            component: CreateEvent
        },
        {
            name : 'Admin-EventDetails',
            path : '/admin/event/details/:eventUrl',
            component: EventDetails
        },
        {
            name : 'Admin-Clubs',
            path : '/admin/clubs',
            template: '<template><div>Clubs!</div></template>'
        },
    ]
}