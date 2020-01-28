<template>
  <div class="app-container">
    <div class="form-container">
      <base-form
        :ref-id="ref"
        :form-options="formOptions"
        :rules="rules"
        :value="form"
        @handleCancel="onCancel"
        @handleSubmit="onSubmit"
      />
    </div>

  </div>
</template>

<script>
import BaseForm from '@/components/basePage/baseForm'
export default {
  components: { BaseForm },
  data() {
    return {
      rules: {
        name: [{ required: true, message: '名字必须' }]
      },
      formOptions: [
        { type: 'input', prop: 'name', label: '姓名', rules: [{ min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }] },
        { type: 'date', prop: 'birth', label: '出生日期' },
        {
          type: 'images', prop: 'file', label: '文件上传', action: 'https://jsonplaceholder.typicode.com/posts/', fileList: [],
          limit: 3,
          tip: '这是一个提示',
          listType: 'picture-card',
          onSuccess: (res, file, fileList) => {
            this.form['fileList'] = fileList
          },
          onRemove: (file, fileList) => {
            this.form['fileList'] = fileList
          }
        },
        {
          type: 'avatar', prop: 'head_image', label: '头像上传', action: 'https://jsonplaceholder.typicode.com/posts/',
          tip: '这是一个提示',
          onSuccess: (res, file, fileList) => {
            this.form.head_image = ''
            this.form.head_image = URL.createObjectURL(file.raw)
          }
        },
        { type: 'rich_text', prop: 'rich_text', label: '富文本', height: 200 }

      ],
      ref: 'baseForm',
      form: {
        name: '123'
      }
    }
  },
  methods: {
    onSubmit() {
      this.$message('submit!')
      console.log(this.form)
    },
    onCancel() {
      this.$message({
        message: 'cancel!',
        type: 'warning'
      })
    }
  }
}
</script>

<style scoped>
.form-container{
  width: 70%;
  margin: 0 auto;
}
</style>

