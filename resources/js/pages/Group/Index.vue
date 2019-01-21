<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <IndexPage :api-url='API_URL' :filters='filters' :pagination='false'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.GroupDialog.open(null)' label='добавить группу' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { GroupList, GroupDialog, API_URL, FILTERS } from '@/components/Group'

export default {
  components: { IndexPage, GroupList, GroupDialog },
  
  data() {
    return {
      API_URL,
      filters: [
        {label: 'Год', field: 'year', type: 'multiple', options: this.$store.state.data.years, valueField: 'id', textField: 'text'},
        {label: 'Преподаватель', field: 'teacher_id', type: 'multiple', options: this.$store.state.data.teachers, valueField: 'id', textField: 'names.abbreviation'},
        {label: 'Предмет', field: 'subject_id', type: 'multiple', options: this.$store.state.data.subjects, valueField: 'id', textField: 'name'},
        {label: 'Класс', field: 'grade_id', type: 'multiple', options: this.$store.state.data.grades, valueField: 'id', textField: 'title'},
        {label: 'Филиал', field: 'branch_id', type: 'multiple', options: this.$store.state.data.branches, valueField: 'id', textField: 'full'},
      ],
    }
  },
  
}
</script>
