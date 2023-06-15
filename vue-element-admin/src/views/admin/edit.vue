<template>
  <div class="form-card">
    <el-form ref="root" label-width="120px" size="small" :model="form" :rules="rules">
      <el-form-item label="用户名" prop="username">
        <el-input v-model="form.username"></el-input>
      </el-form-item>
      <el-form-item label="昵称" prop="nickname">
        <el-input v-model="form.nickname"></el-input>
      </el-form-item>
      <el-form-item label="状态" prop="status">
        <el-switch v-model="form.status"
                   :active-value="ENABLE_STATUS"
                   :inactive-value="DISABLE_STATUS"
        ></el-switch>
      </el-form-item>
      <el-form-item>
        <el-button @click="cancel">取消</el-button>
        <el-button @click="submit" type="primary">提交</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useGetDetail, useSubmit } from '@/views/admin/composables/edit'
import { ENABLE_STATUS, DISABLE_STATUS } from '@/utils/common_options'
import { ElMessage } from 'element-plus'

const route = useRoute()

const { form, item, getDetail } = useGetDetail()
if (route.params.id > 0) {
  onMounted(_ => getDetail(route.params.id))
}

const router = useRouter()
const root = ref(null)
const rules = reactive({
  username: [{ required: true, message: '用户名是必须的' }],
  nickname: [{ required: true, message: '昵称是必须的' }],
  status: [{ required: true, message: '请选择状态' }],
})
const validate = async () => {
  const res = await root.value.validate().catch(err => false)
  return res
}

const {
  submitCreate,
  submitUpdate,
} = useSubmit(form, route.params.id)

const submitFunc = route.params.id > 0 ? submitUpdate : submitCreate

const submit = async () => {
  const v = await validate()
  if (!v) {
    return
  }

  const res = await submitFunc()
  if (res) {
    ElMessage.success('操作成功')
    router.back()
  }
}
const cancel = () => {
  router.back()
}
</script>

<style lang="scss" scoped>
.form-card {
}
</style>
