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

		// auction
		{
			path: "auction",
			component: () =>
				import(
					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/auction/index.vue"
				),
			children: [
				{
					path: "",
					name: "myprocurement-auction",
					component: () =>
						import(
							/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/auction/crud/data.vue"
						),
				},

				{
					path: "create",
					name: "myprocurement-auction-create",
					component: () =>
						import(
							/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/auction/crud/create.vue"
						),
				},

				{
					path: ":auction/edit",
					name: "myprocurement-auction-edit",
					component: () =>
						import(
							/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/auction/crud/edit.vue"
						),
				},

				{
					path: ":auction/show",
					name: "myprocurement-auction-show",
					component: () =>
						import(
							/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/auction/crud/show.vue"
						),
				},
			],
		},

		// history
		{
			path: "history",
			component: () =>
				import(
					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/history/index.vue"
				),
			children: [
				{
					path: "",
					name: "myprocurement-history",
					component: () =>
						import(
							/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/history/crud/data.vue"
						),
				},

				{
					path: ":history/show",
					name: "myprocurement-history-show",
					component: () =>
						import(
							/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/history/crud/show.vue"
						),
				},
			],
		},

		// report
		{
			path: "report",
			name: "myprocurement-report",
			component: () =>
				import(
					/* webpackChunkName: "myprocurement" */ "@modules/myprocurement/frontend/pages/report/index.vue"
				),
		},
	],
};
