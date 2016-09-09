define ['common/Model'], (model) ->
  class model extends model
    loadOne: (params = {}, later = false) ->
      params.url = if 'transfer' of params.data then '/api/transfer-routes' else '/api/routes'
      super(params, later)
        
