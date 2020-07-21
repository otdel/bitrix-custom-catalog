import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';
import SelectorList from './SelectorList';

@inject('cartStore')
@inject('productStore')
@observer
export default class extends React.Component {

  constructor(props) {
    super(props);
    this.state = {
      dataStatus: "pending",
      values: false,
      features: false, 
      data: {},
      displayData: {},
      preparedData: {}, // подготовленные данные, "источник правда" при фильтрации 
      filter: {}, // условия для фильтрации
    }

    this.handleReset = this.handleReset.bind(this);
  }

  handleSubmit(event) {
    event.preventDefault();
    console.log("handleSubmit")
  }

  handleReset() {
    this.filterData({})
    this.setState({filter: {}})
  }

  handleChange = (event) => {
    const filterBySelector = event.target.value.split("&") // kitchenModelName&Novelle
    const featureCode = filterBySelector[0]
    const featureValue = filterBySelector[1]
    const curSelectCondition = []
    curSelectCondition[featureCode] = featureValue
    const filter = {...this.state.filter, ...curSelectCondition}

    this.setState({filter: filter})
    console.log(filter)
    this.filterData(filter)
  }

  saveToState(data) {
    //console.log("saveToState data", data)
    this.setState((state) => {
      return {
        dataStatus: "done",
        values: data.values,
        features: data.features, 
        data: data,
        displayData: data.displayData,
      }
    });
  }

  filterData(filter) {
    const data = this.state.data;
    let displayData = this.state.displayData;
    let filteredData = []
    let filteredDisplayData = []
    let productIds = []

    Object.entries(data.features).map((feature, i) => {
      // устанавливаем ключи характеристик, куда будем записывать уникальные свойства
      filteredDisplayData[feature[0]] = new Set();
    })

    Object.entries(data.values).map((value, i) => {
      let id = value[0];
      let deepValues = value[1].values; // перечень свойств каждого товара
      let arIsFitCondition = []; // сюда записываются результаты соответствия параметрам пользователя

        Object.entries(deepValues).map((deepValue, i) => {
          let key = deepValue[1].featureCode // ключ - код свойства, например, kitchenModelName
          // записываем название свойства для пользователя и его значение в формате имя&значение, добавляем в Set
          Object.entries(filter).map((filterParam) => { // проходим по каждому кастомному свойству и сравниваем с условиями юзера
            if (key === filterParam[0]) {
              if (deepValue[1].featureValue === filterParam[1]) { 
                arIsFitCondition.push(true)
              } else {
                arIsFitCondition.push(false)
              }
            }
          })
        })

        const isEveryTrue = (currentValue) => currentValue === true;
        if (arIsFitCondition.every(isEveryTrue)) {
          filteredData.push(deepValues)
          //console.log(deepValues)
          productIds.push(id)
        }
    })

    Object.entries(filteredData).map((filteredItem, i) => {
      Object.entries(filteredItem[1]).map((filteredValue, i) => {
        let key = filteredValue[1].featureCode // ключ - код свойства, например, kitchenModelName

        filteredDisplayData[key].add(`${filteredValue[1].featureName}&${filteredValue[1].featureValue}`) 
      })
    })

    console.log(productIds)

    
    const cartStore = this.props.cartStore;
    cartStore.setProductIdByFilter(productIds[0]); // записываем ID товара в хранилище, чтобы передать в кнопку AddToCart

    this.setState({displayData: filteredDisplayData})
  }

  componentDidMount() {
    const store = this.props.productStore;
    store.setUserId(this.props.userId); // записываем ID пользователя в хранилище!
    store.getProperties(this.props.productId, this.props.iblockId, this.props.wareId); // получаем данные о корзине пользователя и пишем в стор

    // ждем когда данные станут доступны в store
    function checkStoreState() {
      if(store.state == "pending") {
        window.setTimeout(aCheckStoreState, 50); // проверяем каждые 50 мс
      } else {
        // получаем и подготавливаем данные с бекенда
        const properties = store.state === "done" ? store.curProperties : "{}";
        const data = JSON.parse(properties);
        
        let displayData = [];
        let productIds = [];

        Object.entries(data.features).map((feature, i) => {
          displayData[feature[0]] = new Set(); // устанавливаем ключи характеристик, куда будем записывать уникальные свойства
        })

        Object.entries(data.values).map((value, i) => {
          const id = value[0]
          productIds.push(id)

          let deepValues = value[1].values; // перечень свойств каждого товара
          Object.entries(deepValues).map((deepValue, i) => {
            let key = deepValue[1].featureCode // ключ - код свойства, например, kitchenModelName
            // записываем название свойства для пользователя и его значение в формате имя&значение, добавляем в Set
            if (key === "outerHoleDiameter") { // TODO: для теста функционала
              //deepValue[1].featureValue = Math.floor(Math.random() * Math.floor(1500)).toString();
            }
            displayData[key].add(`${deepValue[1].featureName}&${deepValue[1].featureValue}`) 

          })
        })

        if (productIds.length === 1) {
          document.querySelector('.react-add-to-cart-button').setAttribute('disabled', 'false')
        }
        data.displayData = displayData;
        console.log(data)
        this.saveToState(data);
      }
    } 

    let aCheckStoreState = checkStoreState.bind(this); // биндим контекст, чтобы можно было вызывать функции класса 
    aCheckStoreState();
  }

  //example: http://custom/catalog/cooker_hoods/elikor-rotonda-900-bezhevyy-korpus-vytyazhka-kupol-naya-nabor-bergamo-s-patinoy-seryy-yasen-p125gam-cvet-shozhiy-s-dekorom-s-serebryanoy-patinoy-volokno-vertikal-no-vstavka-1_net-421.0403.733.84/
  render() {
    return (
      <div>
        <SelectorList 
          dataStatus={this.state.dataStatus} 
          displayData={this.state.displayData} 
          filter={this.state.filter}
          handleSubmit={this.handleSubmit}
          handleChange={this.handleChange}
          handleReset={this.handleReset}
        />
      </div>
    );
  }
}