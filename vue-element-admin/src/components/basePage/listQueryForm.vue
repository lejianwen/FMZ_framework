<template>
  <el-card shadow="always">
    <el-form
      ref="listQueryForm"
      :model="currentValue"
      :label-position="labelPosition"
      :label-width="labelWidth"
      :size="size"
      v-bind="$attrs"
      class="list-query-form"
    >
      <el-row :gutter="10">
        <el-col v-for="(formItem,index) in formOptions" :key="index" :span="formItem.span ? formItem.span : 6">
          <el-form-item :label="formItem.label" :prop="formItem.prop">
            <el-input v-if="formItem.type === 'input'" v-model="currentValue[formItem.prop]" clearable />
            <el-input-number v-if="formItem.type === 'number'" v-model="currentValue[formItem.prop]" clearable />
            <el-select v-if="formItem.type === 'select'" v-model="currentValue[formItem.prop]" clearable @change="handleSelectChange({formItem, $event})">
              <el-option
                v-for="item in formItem.options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
            <el-date-picker
              v-if="formItem.type === 'date'"
              v-model="currentValue[formItem.prop]"
              :format="formItem.format ? formItem.format : 'yyyy-MM-dd'"
              :value-format="formItem.vauleFormat ? formItem.vauleFormat : 'yyyy-MM-dd'"
              type="date"
              :placeholder="formItem.placeholder || '选择日期'"
            />
            <el-date-picker
              v-if="formItem.type === 'datetime'"
              v-model="currentValue[formItem.prop]"
              :format="formItem.format ? formItem.format : 'yyyy-MM-dd HH:mm:ss'"
              :value-format="formItem.valueFormat ? formItem.valueFormat : 'yyyy-MM-dd HH:mm:ss'"
              type="datetime"
              :placeholder="formItem.placeholder || '选择时间'"
            />
            <el-date-picker
              v-if="formItem.type === 'datetimerange'"
              v-model="currentValue[formItem.prop]"
              :format="formItem.format || 'yyyy-MM-dd HH:mm:ss'"
              :value-format="formItem.vauleFormat || 'yyyy-MM-dd HH:mm:ss'"
              type="datetimerange"
              :placeholder="formItem.placeholder || '选择时间'"
              :unlink-panels="!!formItem.unlinkPanels"
              range-separator="-"
              start-placeholder="开始时间"
              end-placeholder="结束时间"
              :picker-options="formItem.pickerOptions||[]"
              style="width: 100%"
            />
            <el-date-picker
              v-if="formItem.type === 'daterange'"
              v-model="currentValue[formItem.prop]"
              :format="formItem.format ? formItem.format : 'yyyy-MM-dd'"
              :value-format="formItem.vauleFormat ? formItem.vauleFormat : 'yyyy-MM-dd'"
              type="daterange"
              :unlink-panels="!!formItem.unlinkPanels"
              range-separator="-"
              start-placeholder="开始日期"
              end-placeholder="结束日期"
              :picker-options="formItem.pickerOptions||[]"
              :default-time="['00:00:00', '23:59:59']"
              style="width: 100%"
            />
          </el-form-item>
        </el-col>
        <!--其他筛选项-->
        <slot name="otherFormItem" />
        <el-col :span="24">
          <el-form-item>
            <el-button type="danger" icon="el-icon-refresh" @click="resetForm">
              重置
            </el-button>
            <el-button type="primary" icon="el-icon-search" @click="handleFilter">
              搜索
            </el-button>
            <slot name="otherButton" />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </el-card>

</template>

<script>

export default {
  name: 'ListQueryForm',
  props: {
    labelWidth: {
      type: String,
      default: '80px'
    },
    value: {
      type: Object,
      default() {
        return {}
      }
    },
    formOptions: {
      type: Array,
      default() {
        return []
      }
    },
    size: {
      type: String,
      default: 'mini'
    },
    labelPosition: {
      type: String,
      default: 'right'
    }
  },
  computed: {
    currentValue: {
      get() {
        return this.value
      },
      set(val) {
        this.$emit('update:value', val)
      }
    }
  },
  created() {
  },
  methods: {
    resetForm() {
      this.$refs['listQueryForm'].resetFields()
      // for (const key in this.currentValue) {
      //   if (key !== 'page' && key !== 'page_size') {
      //     this.currentValue[key] = undefined
      //   }
      // }
    },
    handleFilter() {
      for (const key in this.currentValue) {
        if (this.currentValue[key] === '') {
          // this.currentValue[key] = undefined
        }
      }
      this.$emit('handleFilter')
    },
    handleCreate() {
      this.$emit('handleCreate')
    },
    handleSelectChange({ formItem, $event }) {
      this.$emit('handleSelectChange', { formItem, $event })
    }
  }

}
</script>

<style scoped>
  .el-select, .el-input, .el-input-number {
    width: 100%;
  }

  .filter-item.search {
    float: right;
  }
</style>
