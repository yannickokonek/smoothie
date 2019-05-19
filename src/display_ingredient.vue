<template>
	<div class="item-wrapper">
		<span class="text-large">{{item.name}}</span>
		<span class="text-medium">{{item.quantity}} {{item.unit}}</span>
	</div>
</template>

<script type="text/javascript">
	export default {
		props: {
			item: {
				type: Object,
				required: true
			},
		},
	}
</script>
<style type="text/css">
	.text-large {
		font-size: 1.2em;
		font-weight: 511;
		color: #666;
	}
	.text-medium {
		font-size: 1.1em;
		font-weight: 511;
		color: #666;
	}
</style>