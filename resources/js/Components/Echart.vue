<template>
  <!-- Prepare a DOM element for the ECharts chart -->
  <div ref="chart" style="width: 600px; height: 400px;"></div>
</template>

<script setup>
import * as echarts from 'echarts';
import { onMounted, onBeforeUnmount, ref, watch } from 'vue';
import { defineProps } from 'vue';

// Define the `option` prop
const props = defineProps({
  option: {
    type: Object,
    required: true,
  },
});

// Chart DOM reference and instance
const chart = ref(null);
let myChart = null;

const initializeChart = () => {
  if (chart.value && props.option) {
    // Initialize the echarts instance based on the prepared DOM element
    myChart = echarts.init(chart.value);
    myChart.setOption(props.option);

    // Handle window resize event to make the chart responsive
    window.addEventListener('resize', handleResize);
  }
};

const handleResize = () => {
  myChart && myChart.resize();
};

onMounted(() => {
  initializeChart();
});

// Watch for changes to the `option` prop and update the chart if available
watch(
  () => props.option,
  (newOption) => {
    if (myChart && newOption) {
      myChart.setOption(newOption);
    } else if (!myChart && newOption) {
      initializeChart();
    }
  },
  { deep: true }
);

onBeforeUnmount(() => {
  // Clean up the chart instance
  if (myChart) {
    myChart.dispose();
    myChart = null;
  }
  window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
/* Add some optional styles if necessary */
</style>
