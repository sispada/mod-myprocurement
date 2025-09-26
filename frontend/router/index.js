export default {
	path: "/myprocurement",
	meta: { requiredAuth: true },
	component: () =>
		import(
			/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/Base.vue"
		),
	children: [
		{
			path: "",
			redirect: { name: "myprocurement-dashboard" },
		},

		{
			path: "dashboard",
			name: "myprocurement-dashboard",
			component: () =>
				import(
					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/dashboard/index.vue"
				),
		},

		// pagename
		// {
		// 	path: "pagename",
		// 	component: () =>
		// 		import(
		// 			/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/pagename/index.vue"
		// 		),
		// 	children: [
		// 		{
		// 			path: "",
		// 			name: "myprocurement-pagename",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/pagename/crud/data.vue"
		// 				),
		// 		},

		// 		{
		// 			path: "create",
		// 			name: "myprocurement-pagename-create",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/pagename/crud/create.vue"
		// 				),
		// 		},

		// 		{
		// 			path: ":pagename/edit",
		// 			name: "myprocurement-pagename-edit",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/pagename/crud/edit.vue"
		// 				),
		// 		},

		// 		{
		// 			path: ":pagename/show",
		// 			name: "myprocurement-pagename-show",
		// 			component: () =>
		// 				import(
		// 					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/pagename/crud/show.vue"
		// 				),
		// 		},
		// 	],
		// },
	],
};
