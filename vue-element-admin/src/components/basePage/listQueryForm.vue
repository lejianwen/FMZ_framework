<template>
  <el-form
    v-if="formOptions.length"
    ref="listQueryForm"
    :model="currentValue"
    label-position="right"
    :label-width="labelWidth"
    :rules="rules"
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

        </el-form-item>
      </el-col>
      <!--其他筛选项-->
      <slot name="otherFormItem" />
    </el-row>
    <el-row>
      <el-button type="danger" icon="el-icon-refresh" @click="resetForm">
        重置
      </el-button>
      <el-button type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>
      <!--其他按钮-->
      <slot name="otherButton" />
    </el-row>
  </el-form>
</template>

<script>

export default {
  name: 'ListQueryForm',
  props: {
    rules: {
      type: Object,
      default() {
        return {}
      }
    },
    createBtn: {
      type: Boolean,
      default: true
    },
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
