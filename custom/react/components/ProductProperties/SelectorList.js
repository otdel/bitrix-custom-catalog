import React from 'react';
import Selector from './Selector';

export default function SelectorList(props) {
  if (props.dataStatus == "done") {
    let selectors = [];
    Object.entries(props.displayData).map((data, i) => {
      const values = data[1].entries(); // достаем данные с уникальными значениями характеристик товара, тип Set 
      selectors.push(
        <Selector 
          key={data[0]+i} 
          featureCode={data[0]}  
          values={values} 
          handleChange={props.handleChange} 
          canChange={data[1].size > 1 ? "uk-form-success" : ""}
          filter={props.filter}
        />);
    })

    return (
      <form className="uk-grid-small" uk-grid="true" onSubmit={props.handleSubmit} onReset={props.handleReset}>
        <legend className="uk-legend">Выберите характеристики товара</legend>
        {selectors}
        <div className="uk-margin uk-width-1-3@s">
          <button className="uk-button uk-button-primary uk-margin-top" type="submit">Подтвердить</button>
          <button className="uk-button uk-button-default uk-margin-top uk-margin-left" type="reset">Сбросить</button>
        </div>
      </form>
    )
  } else {
    return <div><div uk-spinner="true"></div> Загрузка...</div>;
  }
}
