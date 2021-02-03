<template>
  <el-card shadow="always">
    <el-form
      v-if="formOptions.length"
      :ref="refId"
      :model="currentValue"
      :label-position="labelPosition"
      :label-width="labelWidth"
      :size="size"
      v-bind="$attrs"
      class="base-form"
    >
      <template v-for="(formItem,index) in formOptions">
        <slot v-if="formItem.type==='slot'" :name="formItem.prop" />
        <el-form-item
          v-else
          :key="index"
          :label="formItem.label"
          :prop="formItem.prop"
          :rules="formItem.rules && formItem.rules.length ? formItem.rules : undefined"
        >
          <el-input v-if="formItem.type === 'input'" v-model="currentValue[formItem.prop]" :placeholder="formItem.placeholder" clearable :disabled="formItem.disabled||false" />
          <el-input v-if="formItem.type === 'textarea'" v-model="currentValue[formItem.prop]" :placeholder="formItem.placeholder" clearable type="textarea" :disabled="formItem.disabled||false" />
          <el-input v-if="formItem.type === 'password'" v-model="currentValue[formItem.prop]" :placeholder="formItem.placeholder" clearable type="password" :disabled="formItem.disabled||false" />
          <el-input-number v-if="formItem.type === 'number'" v-model="currentValue[formItem.prop]" :placeholder="formItem.placeholder" clearable :disabled="formItem.disabled||false" />
          <el-select
            v-if="formItem.type === 'select'"
            v-model="currentValue[formItem.prop]"
            :placeholder="formItem.placeholder"
            :multiple="!!formItem.multiple"
            clearable
            :allow-create="formItem.allowCreate||false"
            :disabled="formItem.disabled||false"
            :filterable="formItem.filterable||formItem.allowCreate||false"
            @change="handleSelectChange({formItem, $event})"
          >
            <el-option
              v-for="item in formItem.options"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>

          <el-checkbox
            v-if="formItem.type==='checkbox'"
            v-model="currentValue[formItem.prop]"
            :true-label="formItem.trueLabel||1"
            :false-label="formItem.falseLabel||0"
            :disabled="formItem.disabled||false"
          >{{ formItem.label }}
          </el-checkbox>

          <template v-if="formItem.type === 'checkboxGroup'">
            <el-checkbox v-model="checkAll[formItem.prop]" :indeterminate="isIndeterminate[formItem.prop]" @change="handleCheckAllChange({formItem, $event})">全选</el-checkbox>
            <el-checkbox-group v-model="currentValue[formItem.prop]" @change="handleCheckGroupCheckedChange({formItem, $event})">
              <el-checkbox v-for="item in formItem.options" :key="item.value" :label="item.value" :disabled="item.disabled||false">{{ item.label }}</el-checkbox>
            </el-checkbox-group>
          </template>

          <el-cascader
            v-if="formItem.type==='cascader'"
            v-model="currentValue[formItem.prop]"
            :options="formItem.options"
            :props="formItem.props"
            :show-all-levels="formItem.showAllLevels"
            filterable
            clearable
          />
          <el-switch
            v-if="formItem.type==='switch'"
            v-model="currentValue[formItem.prop]"
            :active-text="formItem.activeText"
            :inactive-text="formItem.inactiveText"
            :active-color="formItem.activeColor||'#13ce66'"
            :inactive-color="formItem.inactiveColor||''"
            :active-value="formItem.activeValue||1"
            :inactive-value="formItem.inactiveValue||0"
            @change="handleSwitchChange({formItem, $event})"
          />
          <el-date-picker
            v-if="formItem.type === 'date'"
            v-model="currentValue[formItem.prop]"
            :format="formItem.format || 'yyyy-MM-dd'"
            :value-format="formItem.vauleFormat || 'yyyy-MM-dd'"
            type="date"
            :placeholder="formItem.placeholder || '选择日期'"
            :disabled="formItem.disabled||false"
          />
          <el-date-picker
            v-if="formItem.type === 'datetime'"
            v-model="currentValue[formItem.prop]"
            :format="formItem.format || 'yyyy-MM-dd HH:mm:ss'"
            :value-format="formItem.valueFormat || 'yyyy-MM-dd HH:mm:ss'"
            type="datetime"
            :placeholder="formItem.placeholder || '选择时间'"
            :disabled="formItem.disabled||false"
          />
          <el-time-picker
            v-if="formItem.type === 'timepicker'"
            v-model="currentValue[formItem.prop]"
            :format="formItem.format || 'HH:mm:ss'"
            :value-format="formItem.valueFormat ||'HH:mm:ss'"
            :placeholder="formItem.placeholder || '选择时间'"
            :picker-options="formItem.options"
            :disabled="formItem.disabled||false"
          />
          <el-time-select
            v-if="formItem.type === 'timeselect'"
            v-model="currentValue[formItem.prop]"
            :placeholder="formItem.placeholder || '选择时间'"
            :picker-options="formItem.options"
            :disabled="formItem.disabled||false"
          />

          <el-upload
            v-if="formItem.type === 'file'"
            :on-success="formItem.onSuccess || null"
            :on-remove="formItem.onRemove||null "
            :before-upload="formItem.beforeUpload||null"
            :name="formItem.name"
            :action="formItem.action"
            :file-list="formItem.fileList"
            :limit="formItem.limit||0"
            :disabled="formItem.disabled||false"
          >
            <el-button size="small" type="primary">点击上传</el-button>
            <div v-if="formItem.tip" slot="tip" class="el-upload__tip">{{ formItem.tip }}</div>
          </el-upload>

          <el-upload
            v-if="formItem.type === 'images'"
            :on-success="formItem.onSuccess||null"
            :on-remove="formItem.onRemove||null"
            :before-upload="formItem.beforeUpload||null"
            :name="formItem.name"
            :action="formItem.action"
            :file-list="formItem.fileList"
            :limit="formItem.limit||0"
            :disabled="formItem.disabled||false"
            :multiple="formItem.multiple||false"
            list-type="picture-card"
          >
            <i class="el-icon-plus" />
            <div v-if="formItem.tip" slot="tip" class="el-upload__tip">{{ formItem.tip }}</div>
          </el-upload>
          <el-upload
            v-if="formItem.type === 'avatar'"
            class="avatar-uploader"
            :name="formItem.name"
            :action="formItem.action"
            :show-file-list="false"
            :on-success="formItem.onSuccess||null"
            :before-upload="formItem.beforeUpload||null"
            :disabled="formItem.disabled||false"
          >
            <img v-if="currentValue[formItem.prop]" :src="currentValue[formItem.prop]" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon" />
            <div slot="tip" class="el-upload__tip">{{ formItem.tip }}</div>
          </el-upload>

          <tinymce v-if="formItem.type==='rich_text'" v-model="currentValue[formItem.prop]" :height="formItem.height ? formItem.height : 300" />
        </el-form-item>
      </template>
      <!--其他筛选项-->
      <slot name="otherFormItem" />
      <el-form-item :label-width="labelWidth">
        <el-button @click="handleCancel">
          取消
        </el-button>
        <el-button type="primary" @click="handleSubmit">
          提交
        </el-button>
        <!--其他按钮-->
        <slot name="otherButton" />
      </el-form-item>
    </el-form>
  </el-card>
</template>

<script>
import Tinymce from '@/components/Tinymce'
export default {
  name: 'BaseForm',
  components: { Tinymce },
  props: {
    refId: {
      type: String,
      default: 'baseForm'
    },
    labelWidth: {
      type: String,
      default: '120px'
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
  data() {
    return {
      isIndeterminate: {},
      checkAll: {}
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
  mounted() {
  },
  methods: {
    init() {
      this.formOptions.forEach(formItem => {
        if (formItem.type === 'checkboxGroup') {
          this.checkAll = Object.assign({}, this.checkAll, { [formItem.prop]: false })
          this.isIndeterminate = Object.assign({}, this.isIndeterminate, { [formItem.prop]: false })
          this.handleCheckGroupCheckedChange({ formItem, $event: undefined })
        }
      })
    },
    handleCancel() {
      this.$emit('handleCancel')
    },
    handleSubmit() {
      this.$refs[this.refId].validate((valid) => {
        this.$emit('handleSubmit', valid)
      })
    },
    handleSelectChange({ formItem, $event }) {
      this.$emit('handleSelectChange', { formItem, $event })
    },
    handleSwitchChange({ formItem, $event }) {
      this.$emit('handleSwitchChange', { formItem, $event })
    },
    handleCheckAllChange({ formItem, $event }) {
      this.checkAll[formItem.prop] = $event
      // this.checkAll = Object.assign({}, this.checkAll)
      this.currentValue[formItem.prop] = $event ? formItem.options.map(item => item.disabled ? '' : item.value).filter(pro => pro !== '') : []
      this.isIndeterminate[formItem.prop] = false
      this.$emit('handleCheckAllChange', { formItem, $event })
    },

    handleCheckGroupCheckedChange({ formItem, $event }) {
      const allLength = formItem.options.filter(op => !op.disabled).length
      this.checkAll = Object.assign({}, this.checkAll, { [formItem.prop]: !!(allLength && this.currentValue[formItem.prop].length === allLength) })
      this.isIndeterminate = Object.assign({}, this.isIndeterminate, { [formItem.prop]: !!(this.currentValue[formItem.prop].length > 0 && this.currentValue[formItem.prop].length < allLength) })
      this.$emit('handleCheckGroupCheckedChange', { formItem, $event })
    }
  }

}
</script>

<style>
</style>
<style lang="scss" scoped>
</style>
