<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<html>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
    <link href="/chat.css" type="text/css" rel="stylesheet">

</head>
<body>
    <div class="container" id="zap">
        <h3 class=" text-center">@{{title}}</h3>
        <div class="messaging">
          <div class="inbox_msg">
            <div class="inbox_people">
              <div class="headind_srch">
                <div class="recent_heading">
                  <h4>Recent</h4>
              </div>
              <div class="srch_bar">
                  <div class="stylish-input-group">
                    <input type="text"  class="search-bar"  placeholder="Search" >
                    <span class="input-group-addon">
                        <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                    </span> </div>
                </div>
            </div>
            <div class="inbox_chat">
                <div class="chat_list active_chat cursor-pointer" style="cursor:pointer;" v-for="row in chats" @click="selectChat(row)" :class="[row.telefone == numero_selecionado ? 'active' : '']">
                  <div class="chat_people">
                    <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                    <div class="chat_ib">
                      <h5>@{{ row.nome }}<span class="chat_date">Dec 25</span></h5>
                      <p></p>
                  </div>
              </div>
          </div>
          

</div>
</div>
<div class="mesgs">
  <div class="msg_history">
    <div class="incoming_msg" v-for="(row,key) in mensagens_chat">
      <div  :class="['received_msg content_type_'+row.messages[0].type]">
        <div class="received_withd_msg">
             <div v-if="row.messages[0].type == 'text'" v-html="row.messages[0].text.body" style="white-space: pre-line">
             </div>
            <div v-if="row.messages[0].type == 'image'">
                Mensagem de "IMAGEM"
          </div>
          <div v-if="row.messages[0].type == 'audio'">
            MENSAGEM DE AUDIO
        </div>
        <div v-if="row.messages[0].type == 'video'">
          mensagem de video
    </div>
</div>
</div>
</div>

</div>
<div class="type_msg">
    <div class="input_msg_write">
      <input type="text" ref="mensagem_texto" class="write_msg" placeholder="Type a message" />
      <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
  </div>
</div>
</div>
</div>


<p class="text-center top_spac"> Design by <a target="_blank" href="https://www.linkedin.com/in/sunil-rajput-nattho-singh/">Sunil Rajput</a></p>

</div></div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.13/dist/vue.js"></script>
<script src="/firebase.js"></script>

<script type="text/javascript">
  var config = {
      apiKey: "xxxxxxxxxxx",
      authDomain: "xxxxxxxxxxxx",
      databaseURL: "xxxxxxxxxxxxxxxxx",
      projectId: "projeto-de-teste-chat",
      storageBucket: "projeto-dxxxchat.appspot.cx",
      messagingSenderId: "2654654xxx",
      appId: "1:265465475348xxx"
  };
  firebase.initializeApp(config);
</script>
<script type="text/javascript">
  new Vue({
    el: '#zap',
    data () {
      return {
        title: 'ZAP ZAP',
        emoji_enable:false,
        showAtalhosSelect: false,
        searChtag: '',
        update: {},
        empresa_contato: {},
        tab_info: 'pendencias',
        autoScrool: true,
        scroolToidMessage: false,
        opcoes: {somente_meu_atendimento: false},
        chat_selecionado: {},
        ultima_mensagem_data: '',
        tab_chats: 'em_aberto',
        total_em_aberto: 0,
        total_em_atendimento: 0,
        total_historico: 0,
        total_pendentes: 0,
        historico: 0,
        lastView: '',
        numero_selecionado: false,
        rolagem: true,
        mensagens: [],
        mensagens_datas: [],
        loading_data: false,
        ultima_mensagem_tempo: 35,
        enviando:false,
        total_mensagens: 0,
        placeholderMensagem: '',
        contato_cnpj: {},
        status_mensagens: [],
        dbRef: '',
        pesquisar: '',
        chats_lista: [],
        conversas: [],
        leftBar: false,
        teste_file: {},
        carregando_chat:false,
        carregando_chat_top:false,
        complet_load_mensagens: false,
        novas_mensagens: [],
        pendencia: {notificacoes:  false, notificar_dias:3},
        atalhos: {tab:'lista',form:{},
        lista: []
    }
}
},

computed:{
    TAGsSearch(){
      let lista= this.atalhos.lista;
      if(this.searChtag){
        lista = lista.filter(row =>{
          return row.atalho.includes(this.searChtag);
      });
    }

    return lista;
},
atendentes_filtered(){
  let lista = this.atendentes;
  if(this.chat_selecionado.pesquisa_atendente){
    lista = lista.filter(row =>{
      return row.name.toLowerCase().includes(this.chat_selecionado.pesquisa_atendente.toLowerCase());
  });
}
return lista;
},
mensagens_chat(){

  let lastNumber = "";
  let lastViewC;
  if(!this.numero_selecionado){
    return;
}
let lista_group = [];
          /// filtrar mensagens
          this.status_mensagens = this.mensagens.filter(row =>{
            return row.entry[0].changes[0].value.hasOwnProperty('statuses');
        });


          let lista = this.mensagens.filter(row =>{
            return !row.entry[0].changes[0].value.hasOwnProperty('statuses') && row.entry[0].changes[0].value.hasOwnProperty('contacts');
        });
          
          lista = lista.map(row =>{
            if(lastViewC == row.entry[0].changes[0].value.messages[0].from){
                row.entry[0].changes[0].value.showFoto = false;
            }else{
                row.entry[0].changes[0].value.showFoto = true;
            }
            // definir se a direcao da mensagem
            if(row.entry[0].changes[0].value.messages[0].from == this.numero_selecionado){
                row.entry[0].changes[0].value.classLeft = "chat-left";
            }else{
                row.entry[0].changes[0].value.classLeft = "chat_admin";
            }
            row.entry[0].changes[0].value.chave = row.chave;
            row.entry[0].changes[0].value.statuMs = row.status ?? '';
            return row.entry[0].changes[0].value;
        });

          /*/
          
          lista = lista.filter(row =>{
            return row.contacts[0].wa_id === this.numero_selecionado;
          });
          /*/

          lista = lista.sort((prev, curr) => prev.messages[0].timestamp - curr.messages[0].timestamp);
          

          this.total_mensagens = lista.length;
          return lista;
      },
      contatosList(){
          let lista = this.contatos;
          if(this.pesquisar){
            lista = lista.filter( row =>{
              return row.nome_user.toLowerCase().includes(this.pesquisar.toLowerCase());
          });
        }
        return lista;
    },
    getEmpresaContato(){
      let contatoLocal = this.contatos.find(row1 =>{
        return row1.numero == this.numero_selecionado;
    });

      if(contatoLocal){
        this.empresa_contato = contatoLocal;
    }else{
       this.empresa_contato = {};
   }
},
chats(){

 lista = this.chats_lista;
 
 if(this.pesquisar){
  lista = lista.filter( row =>{
    return row.telefone.includes(this.pesquisar) || row.nome.toLowerCase().includes(this.pesquisar.toLowerCase()) || row.empresa?.nome_fantasia.toLowerCase().includes(this.pesquisar.toLowerCase()); 
});
}

lista  = lista.sort(function(a, b) { 
  a = new Date(a.ultima_mensagem);
  b = new Date(b.ultima_mensagem);
  return a >b ? -1 : a < b ? 1 : 0;
})

console.log(lista);

return lista;
}
},

async  mounted(){
    this.dbRef = firebase.database().ref();
    /// carregar conversas chats
    const notifyRef = this.dbRef.child('whatsapp_chat').child('chats_numeros');

    notifyRef.on('child_added', (data1) => {
        let data = data1.val();
        console.log(data);
        this.chats_lista.push(data);
    });

    /// detectar novos chats
    notifyRef.on('child_changed', (data1) => {
        let data = data1.val();
        let index = this.chats_lista.findIndex(x => x.telefone == data.telefone);
        this.chats_lista[index].ultima_mensagem = data.ultima_mensagem;
    });

},
methods:{
    async  selectChat(row){
        /// reset array de mensagens
        await Vue.set(this,'mensagens',[]); 
        this.mensagens_datas = [];
        this.carregando_chat = true;
        this.complet_load_mensagens = false;
        scroolToidMessage = false;

        /// ler database do telefone 
        mensagensList = this.dbRef.child('whatsapp_chat').child('mensagens').child(''+row.telefone+'');
        // remover escutadores anteriores
        mensagensList.off('child_added');

        /// Escutar novas mensagens
        mensagensList.on('child_added', (data1) => {
            let data = data1.val();
            this.mensagens.push(data.data);
        });

        this.$refs.mensagem_texto.value = "";
        Vue.set(this,'numero_selecionado',row.telefone);
        Vue.set(this,'chat_selecionado',row);
    },

    sendMensagem(){
      this.rolagem = true;
      let mensagem = this.$refs.mensagem_texto.value;

      if(!mensagem){
        this.$refs.mensagem_texto.focus();
        return;
    }

    this.enviando = true;
    axios.post('/admin/develloper/mensagens_whatsapp/p/sendText',{
        responseType: 'json',
        para: this.numero_selecionado,
        contatoNome: this.chat_selecionado.nome,
        texto: mensagem,
        ultima_mensagem: this.ultima_mensagem_tempo
    })
    .then((response) => {
        let data =  response.data;
        this.$refs.mensagem_texto.value = "";
        this.$refs.mensagem_texto.focus();
        this.enviando = false;
        this.SetBottomBarAfterRead();
    }).catch((err) =>{
        alert('Falha ao enviar,tente novamente!');
        this.enviando = false;
    });
        /// Limpar mensagem

    },

}
})
</script>
</body>
</html>