import NotFound from './components/NotFound';

let Home = ()=> import(/* webpackChunkName: "Admin-Home"*/'./components/Admin/Home');
let CreateEvent = ()=> import(/* webpackChunkName: "Admin-CreateEvent"*/'./components/Admin/Management/CreateEvent');
let EventDetails = ()=> import(/* webpackChunkName: "Admin-EventsList"*/'./components/Admin/Management/EventDetails');
let EventsList = ()=> import(/* webpackChunkName: "Admin-EventsList"*/'./components/Admin/Management/EventsList');

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
            name : 'Admin-EventsList',
            path : '/admin/events/manage',
            component: EventsList
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
        }
    ]
}