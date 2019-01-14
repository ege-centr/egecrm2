<template>
  <div>
    <GroupActDialog ref='GroupActDialog' :group-id='groupId' @updated='loadData' />
    <AddBtn label='добавить акт' class='mb-3' @click.native='$refs.GroupActDialog.open(null)' />
    <Loader v-if='loading' />
    <v-data-table v-else v-show='items.length'
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
    <template slot='items' slot-scope="{ item }">
      <td width='200'>
        <a @click='$refs.GroupActDialog.open(item.id)'>
          Акт №{{ item.id }}
        </a>
      </td>
      <td>
        <span v-if='item.teacher_id'>
          {{ teachers.find(e => e.id == item.teacher_id).names.abbreviation }}
        </span>
      </td>
      <td>
        <span v-if='item.sum'>
          {{ item.sum }} руб.
        </span>
      </td>
      <td>
        <span v-if='item.lesson_count'>
          {{ item.lesson_count }} занятий
        </span>
      </td>
      <td>
        <span v-if='item.date'>
          {{ item.date | date }}
        </span>
      </td>
      <td class='grey--text'>
        {{ item.createdAdmin.name }} {{ item.created_at | date-time }}
      </td>
    </template>
  </v-data-table>
  </div>
</template>
<script>

import { GROUP_ACTS_API_URL } from '@/components/Group'

// TODO: разобраться почему не импортируется из index.js
import GroupActDialog from '@/components/Group/Act/Dialog'

export default {
  props: {
    groupId: {
      type: Number,
      required: true,
    },
  },

  components: { GroupActDialog  },

  data() {
    return {
      loading: true,
      teachers: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    async loadData() {
      this.loading = true
      if (this.teachers === null) {
        await axios.get(apiUrl('teachers')).then(r => {
          this.teachers = r.data
        })
      }
      await axios.get(apiUrl(GROUP_ACTS_API_URL) + queryString({group_id: this.groupId})).then(response => {
        this.items = response.data
      })
      this.loading = false
    },
  },
}
</script>
