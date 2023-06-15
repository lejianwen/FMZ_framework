import { reactive, ref } from 'vue'
import { create, detail, update } from '@/api/admin'
import { ElMessage } from 'element-plus'
import { useRouter } from 'vue-router'

export function useGetDetail () {
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

export function useSubmit (form) {

  const submitCreate = async () => {
    const res = await create(form.value).catch(_ => false)
    return res.code === 0
  }

  const submitUpdate = async () => {
    const res = await update(form.value).catch(_ => false)
    return res.code === 0
  }

  return {
    submitCreate,
    submitUpdate,
  }
}


