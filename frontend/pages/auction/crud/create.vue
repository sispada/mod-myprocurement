<template>
	<form-create with-helpdesk>
		<template
			v-slot:default="{ combos: { officers, methods, types, workunits }, record }"
		>
			<v-card-text>
				<v-row dense>
					<v-col cols="12">
						<v-textarea
							label="Nama Paket"
							rows="2"
							v-model="record.name"
							hide-details
						></v-textarea>
					</v-col>

					<v-col cols="12">
						<v-currency-field
							label="Pagu"
							v-model="record.ceiling"
							hide-details
						></v-currency-field>
					</v-col>

					<v-col cols="6">
						<v-select
							:items="types"
							label="Tipe"
							v-model="record.type_id"
							hide-details
							@update:model-value="fetchMinValue($event, record, types)"
						></v-select>
					</v-col>

					<v-col cols="6">
						<v-select
							:items="methods"
							label="Metode"
							v-model="record.method_id"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="4">
						<v-select
							:items="[
								{ title: 'JANUARI', value: 1 },
								{ title: 'FEBRUARI', value: 2 },
								{ title: 'MARET', value: 3 },
								{ title: 'APRIL', value: 4 },
								{ title: 'MEI', value: 5 },
								{ title: 'JUNI', value: 6 },
								{ title: 'JULI', value: 7 },
								{ title: 'AGUSTUS', value: 8 },
								{ title: 'SEPTEMBER', value: 9 },
								{ title: 'OKTOBER', value: 10 },
								{ title: 'NOVEMBER', value: 11 },
								{ title: 'DESEMBER', value: 12 },
							]"
							label="Bulan"
							v-model="record.month"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="2">
						<v-text-field
							label="Tahun"
							v-model="record.year"
							hide-details
						></v-text-field>
					</v-col>

					<v-col cols="6">
						<v-select
							:items="[
								'APBN',
								'APBNP',
								'APBD',
								'APBDP',
								'PHLN',
								'PNBP',
								'BUMN',
								'BUMD',
							]"
							label="Sumber Dana"
							v-model="record.source"
							hide-details
						></v-select>
					</v-col>

					<v-col cols="12" v-if="record.mode === 'PPBJ'">
						<v-combobox
							:items="officers"
							:return-object="false"
							label="PPBJ"
							v-model="record.officer_id"
							hide-details
						></v-combobox>
					</v-col>

					<v-col cols="12">
						<v-combobox
							:items="workunits"
							:return-object="false"
							label="Unit Kerja"
							v-model="record.workunit_id"
							hide-details
						></v-combobox>
					</v-col>
				</v-row>
			</v-card-text>
		</template>
	</form-create>
</template>

<script>
export default {
	name: "myprocurement-auction-create",

	data: () => ({
		minValue: false,
	}),

	methods: {
		fetchMinValue: function (type, record, types) {
			type = types.find((t) => t.value === type);

			record.mode = record.ceiling <= parseFloat(type.min) ? "PPBJ" : "POKJA";
		},
	},
};
</script>
