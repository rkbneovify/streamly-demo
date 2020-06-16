//
//  SearchVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/29/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class SearchVC: UIViewController {

    // MARK: Properties
    
    static let storyboardIdentifier = "SearchVC"
    private let cellComposer = DataItemCellComposer()
    private let allDataItems = DataItem.sampleItems
    private var filteredDataItems = DataItem.sampleItems
    var filterString = "" {
        didSet {
            // Return if the filter string hasn't changed.
            guard filterString != oldValue else { return }

            // Apply the filter or show all items if the filter string is empty.
            if filterString.isEmpty {
                filtterdChannelList = self.channelList
            }
            else {
              //  filtterdChannelList = self.channelList?.message?.data?.filter { ($0.name?.localizedStandardContains(filterString) ?? <#default value#>) }
            }

            // Reload the collection view to reflect the changes.
            collectionView?.reloadData()
        }
    }
    var channelList:ChannelResponse?
      {
          didSet
          {
              self.collectionView.reloadData()
          }
      }
     var filtterdChannelList:ChannelResponse?
     lazy var viewModel: SearchDataViewModel = {
          return SearchDataViewModel()
      }()

    //  MARK: Outlets

    @IBOutlet weak var collectionView:UICollectionView!
    

    
    //  MARK: LifeCycle

    override func viewDidLoad() {
        super.viewDidLoad()
        self.initialSetup()

    }

    //  MARK: Helper

    private func initialSetup()
    {
        self.collectionView.layoutIfNeeded()
        self.collectionView.layoutSubviews()
        self.makeApiCall()
        self.initVM()
    }

    func makeApiCall(){
        self.showSpinner(onView: self.view)
          viewModel.delegate = self
          viewModel.getSearchData()

      }

      private func initVM() {

          viewModel.searchData = { contactModel in
            self.channelList = contactModel
            self.filtterdChannelList = contactModel
          //  print(self.channelList?.message?.data?[0])

              self.removeSpinner()


          }

      }

}

// MARK: UICollectionViewDataSource

extension SearchVC:UICollectionViewDataSource
{
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return filtterdChannelList?.message?.data?.count ?? 0
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        // Dequeue a cell from the collection view.
        return collectionView.dequeueReusableCell(withReuseIdentifier: DataItemCollectionViewCell.reuseIdentifier, for: indexPath)
    }


}

extension SearchVC:UICollectionViewDelegate

{
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? DataItemCollectionViewCell else { fatalError("Expected to display a `DataItemCollectionViewCell`.") }
        let item = filteredDataItems[(indexPath as NSIndexPath).row]

        // Configure the cell.
        cellComposer.compose(cell, withDataItem: item)
    }
}

// MARK: UISearchResultsUpdating

extension SearchVC:UISearchResultsUpdating
{
    
    func updateSearchResults(for searchController: UISearchController) {
        filterString = searchController.searchBar.text ?? ""
    }
}

extension SearchVC:HandleError
{
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }

}



