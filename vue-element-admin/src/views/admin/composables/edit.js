import { reactive, ref } from 'vue'
import { create, detail, update } from '@/api/admin'
import { ElMessage } from 'element-plus'
import { useRouter } from 'vue-router'

export function useLoadDetail () {
  let item = ref({})  //保留原始值
  let form = ref({})
  const getDetail = async (id) => {
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

export function useActions (form) {
  const router = useRouter()
  const root = ref(null)
  const rules = reactive({
    username: [{ required: true, message: '用户名是必须的' }],
    nickname: [{ required: true, message: '昵称是必须的' }],
    status: [{ required: true, message: '请选择状态' }],
  })

  const submit = async () => {
    const v = await root.value.validate().catch(err => false)
    if (!v) {
      return
    }
    if (form.value.id > 0) {
      const res = await update(form.value).catch(_ => false)
      if (res) {
        ElMessage.success('操作成功')
        router.back()
      }
    } else {
      const res = await create(form.value).catch(_ => false)
      if (res) {
        ElMessage.success('操作成功')
        router.back()
      }
    }
  }

  const cancel = () => {
    router.back()
  }

  return {
    submit,
    cancel,
    rules,
    root,
  }
}
