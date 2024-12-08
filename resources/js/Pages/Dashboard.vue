<script setup>
import { onMounted, ref, watch } from "vue";
import { formatDate } from "@/helpers";
import { usePage } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Echart from "@/Components/Echart.vue";

defineProps({
  activities: Array
});

let data = [
  ////////////////////////////////////////
  [
    { name: '圣彼得堡来客', value: 5.6 },
    { name: '陀思妥耶夫斯基全集', value: 1 },
    { name: '史记精注全译（全6册）', value: 0.8 },
    { name: '加德纳艺术通史', value: 0.5 },
    { name: '表象与本质', value: 0.5 },
    { name: '其它', value: 3.8 }
  ],
];

const chartOption = ref({
  title: {
    text: '阅读书籍分布',
    left: 'center',
    textStyle: {
      color: '#999',
      fontWeight: 'normal',
      fontSize: 14
    }
  },
  series: data.map(function (data, idx) {
    var top = idx * 100;
    return {
      type: 'pie',
      radius: [20, 60],
      top: top + '%',
      height: '100%',
      left: 'center',
      width: 400,
      itemStyle: {
        borderColor: '#fff',
        borderWidth: 1
      },
      label: {
        alignTo: 'edge',
        formatter: '{name|{b}}\n{time|{c} 小时}',
        minMargin: 5,
        edgeDistance: 10,
        lineHeight: 15,
        rich: {
          time: {
            fontSize: 10,
            color: '#999'
          }
        }
      },
      labelLine: {
        length: 15,
        length2: 0,
        maxSurfaceAngle: 80
      },
      data: data
    };
  })
});

const logDescription = (log) => {
  return `${log.role} ${log.event} data from ${log.subject_type} table`;
}
</script>

<template>
  <MainLayout module="Dashboard" header-module="Home">
    <div class="row my-4">
      <div class="col-xl-8">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card rounded-sm shadow statistics">
              <div class="card-body">
                <div class="logo">
                  <i class="bi bi-currency-exchange"></i>
                </div>
                <div class="info">
                  <p class="title">total revenue</p>
                  <h4 class="total">$3,400 <small>23.4% <i class="bi bi-caret-up-fill"></i></small></h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card rounded-sm shadow statistics">
              <div class="card-body">
                <div class="logo">
                  <i class="bi bi-currency-exchange"></i>
                </div>
                <div class="info">
                  <p class="title">total revenue</p>
                  <h4 class="total">$3,400 <small>23.4% <i class="bi bi-caret-up-fill"></i></small></h4>
                </div>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card rounded-sm shadow statistics">
              <div class="card-body">
                <div class="logo">
                  <i class="bi bi-currency-exchange"></i>
                </div>
                <div class="info">
                  <p class="title">total revenue</p>
                  <h4 class="total">$3,400 <small>23.4% <i class="bi bi-caret-up-fill"></i></small></h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card rounded-sm shadow statistics">
              <div class="card-body">
                <div class="logo">
                  <i class="bi bi-currency-exchange"></i>
                </div>
                <div class="info">
                  <p class="title">total revenue</p>
                  <h4 class="total">$3,400 <small>23.4% <i class="bi bi-caret-up-fill"></i></small></h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card rounded-sm shadow">
              <div class="card-body">
                <div class="chart-responsive d-flex justify-content-center">
                  <Echart :option="chartOption" :auto-resize="true" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4">
        <div class="card rounded-sm mb-3 shadow">
          <div class="card-header border-b-0 bg-cyan-50">
            <h6 class="card-title text-cyan-500 font-medium">
              <i class="fa-solid fa-list-ul"></i>
              Activity Logs
            </h6>

            <div class="card-tools">
              <a href="#" class="text-xs">See more</a>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <ul class="activity-feed">
                  <li v-for="item in activities" :key="item.id" :class="['item ' + item.event]">
                    <span>{{ logDescription(item) }}</span>
                    <div class="time">{{ formatDate(item.created_at) }}</div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
