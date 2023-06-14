import { reactive, ref } from 'vue'
import { create, detail, update } from '@/api/admin'
import { ElMessage } from 'element-plus'
import { useRouter } from 'vue-router'

export function useGetDetail (id) {
  let item = ref({})  //保留原始值
  let form = ref({})
  const getDetail = async () => {
    const res = await detail(id).catch(_ => false)
    if (!res) {
      return
    }
    item.value = { ...res.data }
    form.value = { ...res.data }
  }

  return {
    form,
    item,
    getDetail,
  }
}

export function useSubmit (form, id) {
  const root = ref(null)
  const router = useRouter()
  const rules = reactive({
    username: [{ required: true, message: '用户名是必须的' }],
    nickname: [{ required: true, message: '昵称是必须的' }],
    status: [{ required: true, message: '请选择状态' }],
  })

  const validate = async () => {
    const res = await root.value.validate().catch(err => false)
    return res
  }

  const submitCreate = async () => {
    const res = await create(form.value).catch(_ => false)
    return res.code === 0
  }

  const submitUpdate = async () => {
    const res = await update(form.value).catch(_ => false)
    return res.code === 0
  }
  const submitFunc = id > 0 ? submitUpdate : submitCreate

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

  return {
    root,
    rules,
    validate,
    submit,
    cancel,
  }
}


