<template>
  <div>
    <div class="upload-btn" @click="dialogVisible=true">
      <slot name="default">
        <el-button type="primary">+上传</el-button>
      </slot>
    </div>
    <el-dialog :visible.sync="dialogVisible" custom-class="images" append-to-body top="5vh">
      <div slot="title">图库</div>
      <el-container>
        <el-aside width="150px">
          <div class="group-item " :class="{active:listQuery.group_name === '全部'}" @click="listQuery.group_name='全部'">全部</div>
          <!--        <div class="group-item " :class="{active:listQuery.group_name === '分组1'}" @click="listQuery.group_name='分组1'">分组1</div>-->
        </el-aside>
        <el-main>
          <el-row>
            <el-col :span="12">
              <el-input v-model="listQuery.name" size="small" placeholder="搜索">
                <i slot="suffix" class="el-input__icon el-icon-search" style="cursor: pointer" @click="getList" />
              </el-input>
            </el-col>
            <el-col :span="12" class="upload">
              <el-upload
                ref="upload"
                :on-success="fileUploadSuccess"
                :before-upload="beforeFileUpload"
                name="file"
                :action="type==='oss' ? fileUploadHost : upLoadAction"
                multiple
                :on-error="fileUpErr"
                list-type="picture"
                :data="fileUploadData"
                :on-progress="onProgress"
                accept="image/*"
              >
                <el-button size="small" type="primary" icon="el-icon-plus" style="width: 100px">上传</el-button>
              </el-upload>
            </el-col>
          </el-row>
          <div v-loading="listLoading" class="preview">
            <div v-for="(image,index) in images" :key="index" class="image-item">
              <div class="image-con">
                <el-image :src="image.url" fit="contain" class="image" @click="checkImage(index)" />
                <div class="size"><span>{{ image.image_width }}*{{ image.image_height }}</span><i class="el-icon el-icon-zoom-in" @click="previewImage=true,previewImageUrl=image.url" /> </div>
                <div class="close" @click="deleteItem(image)"><i class="el-icon-close" /></div>
              </div>
              <div class="name">{{ image.origin_filename }}</div>
              <div class="layer" :class="{active: image.checked}" @click="checkImage(index)"><i class="el-icon-check" /></div>
            </div>
          </div>
          <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.page_size" layout="total, prev, pager, next, jumper" @pagination="getList" />
        </el-main>
      </el-container>
      <div slot="footer">
        <el-button type="" @click="dialogVisible=false">取消</el-button>
        <el-button type="primary" @click="confirm">确定</el-button>
      </div>
      <el-dialog :visible.sync="previewImage" append-to-body top="5vh">
        <div style="text-align: center;">
          <el-image :src="previewImageUrl" style="object-fit: contain;" />
        </div>
      </el-dialog>
    </el-dialog>
  </div>
</template>

<script>
import { get_suffix, random_filename } from '@/utils/file'
import { fetchList, deleteItem, getOssToken } from '@/api/file'
import pagination from '@/components/Pagination/index'

export default {
  name: 'ImagesDialog',
  components: { pagination },
  props: {
    type: {
      type: String,
      default: 'oss'
    },
    limit: {
      type: Number,
      default: 0
    },
    value: {
      type: Array,
      default() {
        return []
      }
    }
  },
  data() {
    return {
      clearFilesTimer: null,
      dialogVisible: false,
      fileList: [],
      fileDatafileData: {},
      upLoadAction: process.env.VUE_APP_BASE_API + '/file/upload',
      fileUploadHost: 'http://shenglan-images.oss-cn-beijing.aliyuncs.com',
      fileUploadData: {},
      fileExpire: 0,
      images: [],
      previewImage: false,
      previewImageUrl: '',
      listLoading: false,
      total: 0,
      listQuery: {
        page: 1,
        page_size: 18,
        group_name: '全部',
        name: '',
        _order: { 'id': 'descending' }
      }
    }
  },
  computed: {
    checkedLength() {
      return this.images.filter(item => item.checked).length
    }
  },
  created() {
    this.getList()
  },
  methods: {
    deleteItem(image) {
      this.listLoading = true
      deleteItem({ id: image.id }).then(res => {
        this.$message.success('删除成功')
        this.getList()
      })
    },
    checkImage(index) {
      if (this.limit > 0 && this.checkedLength >= this.limit && !this.images[index].checked) {
        this.$message.warning(`最多选取${this.limit}张`)
        return
      }
      this.images[index].checked = !this.images[index].checked
    },
    getList() {
      fetchList(this.listQuery).then(res => {
        this.images = res.data.list.map(item => {
          item.checked = false
          if (!item.filename.includes('http://') && !item.filename.includes('https://')) {
            item.url = item.host + '/' + item.filename
          }
          return item
        })
        this.total = res.data.total
        this.listLoading = false
      })
    },
    async beforeFileUpload(file) {
      console.log('file', file)
      if (this.type === 'oss') {
        const now = Date.parse(new Date()) / 1000
        if (this.fileExpire < now) {
          await getOssToken().then(res => {
            const obj = res.data
            this.fileExpire = parseInt(obj['expire'])
            this.fileUploadData = {
              // 'key': obj['dir'] + filename,
              'policy': obj['policy'],
              'OSSAccessKeyId': obj['accessid'],
              'success_action_status': '200', // 让服务端返回200,不然，默认会返回204
              'callback': obj['callback'],
              'signature': obj['signature'],
              'x:dir': obj['dir']
            }
            this.fileUploadHost = obj['host']
          })
        }
        await new Promise(resolve => {
          setTimeout(() => { resolve() }, 10)
        })

        this.fileUploadData.key = this.fileUploadData['x:dir'] + random_filename(file.name)
        this.fileUploadData['x:origin_filename'] = file.name
      } else {
        this.fileUploadData = {
          origin_filename: file.name,
          size: file.size,
          mime_type: file.type,
          format: get_suffix(file.name).replace('.', '')
        }
        var reader = new FileReader() // 读取文件
        reader.readAsDataURL(file)
        const image = await new Promise((resolve, reject) => {
          reader.onload = (e) => {
            const imageEl = new Image()
            imageEl.src = e.target.result
            imageEl.onload = (img) => {
              resolve(imageEl)
            }
            imageEl.onerror = (e) => {
              console.log('image.onerror')
              resolve(false)
            }
          }
        })
        if (image) {
          this.fileUploadData.image_width = image.width
          this.fileUploadData.image_height = image.height
        }
      }
      return Promise.resolve()
    },
    fileUpErr() {

    },
    fileUploadSuccess(response, file, fileList) {
      this.listLoading = true
      console.log('fileUploadSuccess', response, file, fileList)
      this.getList()
      if (this.clearFilesTimer) {
        clearTimeout(this.clearFilesTimer)
      }
      this.clearFilesTimer = setTimeout(() => {
        this.$refs.upload.clearFiles()
      }, 700)
    },
    onProgress(e, file, fileList) {
    },
    confirm() {
      this.$emit('input', this.images.filter(item => item.checked).map(item => item.url))
      this.dialogVisible = false
    }
  }
}
</script>

<style scoped lang="scss">
  .upload-btn{
    display: inline-block;
  }
  .el-container {
    border-top: 1px solid #d9d9d9;
    border-bottom: 1px solid #d9d9d9;
    margin-bottom: 20px;

    .el-main {
      overflow: visible;
      padding-bottom: 0;
    }
  }

  .el-aside {
    padding: 20px;
    border-right: 1px solid #d9d9d9;

    .group-item {
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;

      &.active {
        background-color: #409EFF;
        color: #fff;
      }
    }
  }

  .upload {
    text-align: right;
    position: relative;
    z-index: 99;

    /deep/ .el-upload-list--picture {
      position: absolute;
      right: -100px;
      width: 300px;
      z-index: 999;
    }
  }

  .preview {
    padding-top: 10px;

    .image-item {
      display: inline-block;
      margin-right: 10px;
      margin-top: 10px;
      position: relative;

      .image-con {
        display: block;
        position: relative;
        box-sizing: border-box;

        .image {
          width: 110px;
          height: 110px;
          object-fit: contain;
        }

        .size {
          position: absolute;
          width: 100%;
          top: 90px;
          left: 0;
          background-color: #000000;
          opacity: 0.5;
          color: #fff;
          font-size: 14px;
          line-height: 20px;
          height: 20px;
          text-align: center;
          display: none;

          i {
            margin-left: 20px;
            cursor: pointer;
          }
        }

        .close {
          position: absolute;
          top: 0;
          right: 0;
          cursor: pointer;
          background-color: rgba(0, 0, 0, 0.45);
          color: #fff;
          border-radius: 50%;
          width: 15px;
          height: 15px;
          line-height: 15px;
          font-size: 15px;
          display: none;
        }

        &:hover {
          .size {
            display: block;
          }

          .close {
            display: block;
          }
        }
      }

      .name {
        text-align: center;
        color: #333;
        font-size: 12px;
        width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 5px;
      }
      .layer {
        display: none;
        position: absolute;
        left: 0;
        top: 0;
        background-color: rgba(0, 0, 0, 0.45);
        width: 100%;
        height: 115px;
        text-align: center;

        i {
          font-size: 16px;
          line-height: 110px;
          color: #fff;
        }
      }

      .layer.active {
        display: block;
      }
    }

  }

</style>
