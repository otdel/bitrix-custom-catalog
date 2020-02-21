import React from 'react';
import { inject } from 'mobx-react';
import classNames from 'classnames';

@inject('сartStore')
export default class ProductList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
          items: [{
            id: 0,
            name: "Вытяжка Серая 2000 Турбо",
            price: 200
          }, {
            id: 1,
            name: "Вытяжка Золотой Граммофон",
            price: 1000
          }, {
            id: 2,
            name: "Пылесос Гроза котов",
            price: 500
          }]
        }
        this.handleClick = this.handleClick.bind(this);
      }
  
    handleClick = e => {
      this.props.сartStore.addToCart(e);    
    } 
    renderProducts() {
      return this.state.items.map((item, idx) => {
          return (
          <div key = {idx}>
              <div className={classNames({'uk-card': true, 'uk-card-default': true, 'uk-card-body': true})}>
                  <h3 className="uk-card-title">{item.name}</h3>
                  <p>{item.price} &#8381;</p>
                  <button 
                    className={classNames({'uk-button': true, 'uk-button-primary': true})}
                    onClick = {() => this.handleClick(item)}
                  >
                    Добавить в корзину
                  </button>
              </div>
          </div>
          )
      })
    } 
    render() {
        return (
          <div className={classNames({'uk-child-width-1-3@m': true, 'uk-grid-small': true, 'uk-grid-match': true})} uk-grid="true">
            {this.renderProducts()}
          </div>
        );
    }
}