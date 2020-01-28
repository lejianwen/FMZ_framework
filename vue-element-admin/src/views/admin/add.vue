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
const model = 'admin'
import BaseForm from '@/components/basePage/baseForm'
import { createItem, updateItem, itemDetail } from '@/api/admin'
import { fetchList as roleList } from '@/api/adminRole'
import { commonOptions } from '@/utils/options'
export default {
  name: `${model}Add`,
  components: { BaseForm },
  data() {
    return {
      time: '',
      formDisabled: false,
      rules: {
        username: [{ required: true, message: '用户名必须的' }],
        password: [{ required: true, message: '密码必须的' }]
      },
      formOptions: [
        { type: 'select', prop: 'role_id', label: '角色', options: [] },
        { type: 'input', prop: 'username', label: '用户名' },
        { type: 'password', prop: 'password', label: '密码' },
        { type: 'input', prop: 'nickname', label: '昵称' },
        {
          type: 'avatar', prop: 'avatar', label: '头像', name: 'file',
          action: process.env.VUE_APP_BASE_API + '/file/upload',
          onSuccess: (res, file) => {
            // this.$set(this.item, 'image', res.model)
            this.item.avatar = res.data
          }
        },
        { type: 'input', prop: 'telephone', label: '电话' },
        { type: 'input', prop: 'qq', label: 'QQ' },
        { type: 'select', prop: 'status', label: '状态', options: commonOptions.status }
      ],
      ref: 'baseFormChild',
      item: {
        avatar: ''
      },
      oldPassword: ''

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
    roleList({ page_size: 9999 }).then(res => {
      this.formOptions[0].options = res.data.list.map(item => {
        return { label: item.name, value: item.id }
      })
    })
    if (this.isEdit || this.isDetail) {
      itemDetail(this.$route.params.id).then(res => {
        this.item = res.data
        this.oldPassword = res.data.password
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
        this.item.token = undefined
        this.item.token_expire_time = undefined
        if (this.item.password === this.oldPassword) {
          this.item.password = undefined
        }
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
