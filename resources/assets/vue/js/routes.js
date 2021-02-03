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
            path : '/new-admin',
            component : Home
        },
        {
            name : 'Admin-Eventlistings',
            path : '/new-admin/events/manage',
            component: Eventlistings
        },
        {
            name : 'Admin-CreateEvent',
            path : '/new-admin/events/new',
            component: CreateEvent
        },
        {
            name : 'Admin-EventDetails',
            path : '/new-admin/event/details/:eventUrl',
            component: EventDetails
        },
        {
            name : 'Admin-Clubs',
            path : '/new-admin/clubs',
            template: '<template><div>Clubs!</div></template>'
        },
    ]
}