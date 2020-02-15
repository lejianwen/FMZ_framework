<template>
  <div class="app-container">
    <div class="form-container">
      <base-form
        ref="baseForm"
        :ref-id="ref"
        :form-options="formOptions"
        :rules="rules"
        :value="item"
        :disabled="formDisabled"
        @handleCancel="onCancel"
        @handleSubmit="onSubmit"
      />
    </div>

  </div>
</template>

<script>
const model = 'config'
import BaseForm from '@/components/basePage/baseForm'
import { createItem, updateItem, itemDetail } from '@/api/config'
import { commonOptions } from '../../utils/options'

export default {
  name: `${model}Add`,
  components: { BaseForm },
  data() {
    return {
      time: '',
      formDisabled: false,
      rules: {
        name: [{ required: true, message: '名称必须的' }],
        code: [{ required: true, message: '配置码必须的' }]
      },
      formOptions: [
        { type: 'input', prop: 'name', label: '名称' },
        { type: 'input', prop: 'code', label: '配置码' },
        { type: 'input', prop: 'value', label: '配置值' },
        { type: 'select', prop: 'status', label: '状态', options: commonOptions.status }
      ],
      ref: 'baseFormChild',
      item: {
        name: '',
        code: '',
        status: 1
      }

    }
  },
  computed: {
    isEdit() {
      return this.$route.name === `${model}Edit`
    },
    isDetail() {
      return this.$route.name === `${model}Detail`
    }
  },
  created() {
    if (this.isEdit || this.isDetail) {
      itemDetail(this.$route.params.id).then(res => {
        this.item = res.data
      })
    }
    if (this.isDetail) {
      this.formDisabled = true
    }
  },
  methods: {
    onSubmit() {
      if (this.isEdit) {
        this.item.updated_at = undefined
        this.item.created_at = undefined
        updateItem(this.item).then(res => {
          this.$message.success('修改成功')
          this.$router.back()
        })
      } else if (!this.isDetail) {
        createItem(this.item).then(res => {
          this.$message.success('创建成功')
          this.$router.back()
        })
      } else {
        this.$router.back()
      }
    },
    onCancel() {
      this.$router.back()
    },
    updateFormOptions(prop, options) {
      this.formOptions.forEach(v => {
        if (v.prop === prop) {
          v.options = options
        }
      })
    }
  }
}
</script>
<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        display: block;
    }
</style>
<style scoped>

</style>
