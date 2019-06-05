export const ENTITY_TYPE = {
  admin: 'App\\Models\\Admin\\Admin',
  client:  'App\\Models\\Client\\Client',
  representative: 'App\\Models\\Client\\Representative',
  teacher: 'App\\Models\\Teacher'
}

export const ENTITY_TYPE_TITLE = {
  [ ENTITY_TYPE.admin ]: 'админ',
  [ ENTITY_TYPE.client ]: 'клиент',
  [ ENTITY_TYPE.representative ]: 'представитель',
  [ ENTITY_TYPE.teacher ]: 'учитель',
}

export default ENTITY_TYPE