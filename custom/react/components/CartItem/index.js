import React from 'react';
import { inject, observer} from 'mobx-react';
import classNames from 'classnames';

@inject('cartStore')
@observer
export default class CartItem extends React.Component {
    handleClick = e => {
        this.props.cartStore.removeFromCart(e);    
    } 
    render() {
        let {item}= this.props;
        return (
            <ul className={classNames({'uk-list': true, 'uk-list-striped': true})}>
                <li>
                    {item.name} - {item.price}
                    <div className="uk-align-right">
                        <button onClick = {() => this.handleClick(item)} > Удалить </button>
                    </div>
                </li>
            </ul>
        );
    }
}