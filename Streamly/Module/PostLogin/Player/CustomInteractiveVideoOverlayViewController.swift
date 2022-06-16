/*
 See LICENSE folder for this sample’s licensing information.
 
 Abstract:
 The view controller for the interactive video overlay.
 */

import UIKit

class CustomInteractiveVideoOverlay: UIViewController {
    
    // MARK: Outlets
    @IBOutlet weak var headingLbl: UILabel!
    @IBOutlet weak var collectionView: UICollectionView!
    
    // MARK: Properties
    override var preferredFocusEnvironments: [UIFocusEnvironment] {
        return [self.collectionView]
    }
    var selectedIndex = 0
    var data = [ChannelArray]()
    weak var delegate:CustomViews?
    var searchViewModel = SearchDataViewModel()
    
    // MARK: LifeCycle
    override func viewDidLoad() {
        super.viewDidLoad()
        self.inititAlSetup()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        //print("jjh")
        super.viewWillAppear(animated)
        if self.data.count > 0 {
            self.collectionView.scrollToItem(at: IndexPath(item: self.selectedIndex, section: 0), at: .centeredHorizontally, animated: true)
        }
    }
    
    // MARK: Helper
    
    private func inititAlSetup()
    {
        searchViewModel.getAllChannelListForSearch()
        searchViewModel.delegate = self
        self.headingLbl.text = "All Channel"
        self.collectionView.dataSource = self
        self.collectionView.delegate = self
        collectionView.register(UINib(nibName: PosterCell.identiFier, bundle: nil),
                                forCellWithReuseIdentifier: PosterCell.identiFier)
        collectionView.reloadData()
        if data.count > 0 {
            collectionView.scrollToItem(at: IndexPath(item: selectedIndex, section: 0), at: .centeredHorizontally, animated: true)
        }
        
        searchViewModel.searchData = { data in
            self.data = data.data ?? []
            self.collectionView.reloadData()
            if self.data.count > 0 {
                self.collectionView.scrollToItem(at: IndexPath(item: self.selectedIndex, section: 0), at: .centeredHorizontally, animated: true)
            }
           
        }
    }
}

// MARK: CollectionView dataSource
extension CustomInteractiveVideoOverlay:UICollectionViewDataSource
{
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return data.count
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        
        guard let cell = self.collectionView.dequeueReusableCell(withReuseIdentifier: PosterCell.identiFier, for: indexPath) as? PosterCell else {return UICollectionViewCell()}
         let rowData = self.data[indexPath.item]
         cell.confiGureData(rowData, indexPath.item)
        
        return cell
    }
}
extension CustomInteractiveVideoOverlay: UICollectionViewDelegate {
    
    func collectionView(_ collectionView: UICollectionView,
                        canFocusItemAt indexPath: IndexPath) -> Bool {
        return true
    }
    
    func collectionView(_ collectionView: UICollectionView,
                        didSelectItemAt indexPath: IndexPath) {
        // Now, thanks to the function above, this is disabled
        selectedIndex = indexPath.item
        self.delegate?.changeChannel(data[indexPath.item])

    }
}

// MARK: - HandleError
extension CustomInteractiveVideoOverlay: HandleError {
    func handleError(_ message: String, _ statusCode: Int) {
        print(message + "\(statusCode)")
    }
    
    func successAPI() {
        self.collectionView.reloadData()
    }
    
    
}
