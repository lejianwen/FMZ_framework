<template>
  <div class="index">
    <div class="counts">
      <el-card class="item" header="访客数">
        <div class="num">
          <span class="before">
            <span class="exp ">昨日</span>
            <span class="">3000</span></span>
          <span class="middle">
            <span class="exp">当前</span>
            <span>3000</span>
          </span>
          <span class="after">
            <span class="exp ">预计今日</span>
            <span class="green">1500
              <el-icon><el-icon-bottom></el-icon-bottom></el-icon>
            </span>
          </span>
        </div>
      </el-card>
      <el-card class="item" header="订单数">
        <div class="num">
          <span class="before">
            <span class="exp ">昨日</span>
            <span class="">2000</span></span>
          <span class="middle">
            <span class="exp">当前</span>
            <span>3000</span>
          </span>
          <span class="after">
            <span class="exp ">预计今日</span>
            <span class="red">
              5000
                 <el-icon><el-icon-top></el-icon-top></el-icon>
            </span>
          </span>
        </div>
      </el-card>
      <el-card class="item" header="成交数">
        <div class="num">
          <span class="before">
            <span class="exp ">昨日</span>
            <span class="">3000</span></span>
          <span class="middle">
            <span class="exp">当前</span>
            <span>3000</span>
          </span>
          <span class="after">
            <span class="exp ">预计今日</span>
            <span class="red">6000<el-icon><el-icon-top></el-icon-top></el-icon></span>
          </span>
        </div>
      </el-card>
    </div>

    <el-row style="margin-top: 20px">
      <el-col :span="8">
        <el-card class="lans" header="语言详情">
          Vue
          <el-progress :percentage="71.3" color="#42b983"></el-progress>
          JavaScript
          <el-progress :percentage="24.1" color="#f1e05a"></el-progress>
          CSS
          <el-progress :percentage="13.7"></el-progress>
          HTML
          <el-progress :percentage="5.9" color="#f56c6c"></el-progress>
        </el-card>
      </el-col>
      <el-col :span="15" :offset="1">
        <el-card header="待办事项" class="todo">
          <el-table :data="todoList" size="small" :show-header="false">
            <el-table-column prop="title"></el-table-column>
          </el-table>
        </el-card>
      </el-col>
    </el-row>


  </div>
</template>

<script>
  import { defineComponent, ref, onMounted } from 'vue'

  export default defineComponent({
    name: 'Home',
    setup () {
      const todoList = ref([
        {title:'修复bug'},
        {title:'修复bug'},
        {title:'修复bug'},
        {title:'增加新功能'},
      ])
      return {
        todoList
      }
    },
  })
</script>

<style scoped lang="scss">
  .index {
    .counts {
      display: flex;
      justify-content: space-between;

      .item {
        width: 32.5%;

        .num {
          font-size: 28px;
          display: flex;
          justify-content: space-around;
          align-items: center;

          .before, .after {
            font-size: 18px;

            .exp {
              font-size: 12px;
              margin-right: 10px;
            }

            .red {
              color: red;
            }

            .green {
              color: green;
            }
          }

          .middle {
            .exp {
              font-size: 20px;
              margin-right: 10px;
            }

            span + span {
              font-weight: bold;
            }
          }
        }
      }

    }
    .lans, .todo{
      height: 250px
    }
  }
</style>
