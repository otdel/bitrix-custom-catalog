import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('cartStore')
@observer
export default class extends React.Component {
    handleRemoveClick = e => {
        this.props.cartStore.removeFromCart(e);
    }
    render() {
        let {item}= this.props;
        return (
            <tr>
                <td>{item.name}</td>
                <td>
                    <a href={item.link}>
                        <img src={item.picture} width="150" height="auto" />
                    </a>
                </td>
                <td>{item.price}</td>
                <td>
                    
                        <input type="hidden" name="GLOBAL_CART_DATA_PRODUCT_ID" value="product->getId" />
                        <input type="hidden" name="oipCartActionHandler" value="component->getComponentId" />
                        <button className="uk-button" name="GLOBAL_CART_ACTION_NAME"
                            value="GLOBAL_CART_ACTION_REMOVE_PRODUCT"
                            onClick = {() => this.handleRemoveClick(item)}
                        >
                            <i className="uk-icon" uk-icon="close" ></i>
                                Удалить
                        </button>
                    
                </td>
            </tr>
        );
    }
}